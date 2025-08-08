<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use App\Models\ClassSchedules;
use App\Models\ClassBooking;
use App\Models\UserKuota;
use App\Models\UserMembership;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\bookingInvoiceEmail;
use Livewire\Component;
use Carbon\Carbon;

class CheckoutClass extends Component
{
    public $id, $schedule, $cek_quota;
    public $existing_booking;
    public $selected_position = null;
    public $available_positions = [];
    public $is_reformer_class = false;

    public function mount($id)
    {
        $this->id = $id;

        // Get class schedule with relations
        $this->schedule = ClassSchedules::with(['classes.groupClass', 'trainer'])->find($id);

        if (!$this->schedule) {
            abort(404, 'Schedule not found');
        }

        // Check if this is a Reformer class
        $this->is_reformer_class = $this->schedule->classes->groupClass->name === 'REFORMER CLASS';

        // Initialize available positions for Reformer class
        if ($this->is_reformer_class) {
            $this->initializeReformerPositions();
        }

        // Initialize quota to 0
        $this->cek_quota = 0;

        // Check if user already has booking for this schedule
        if (Auth::check()) {
            $this->existing_booking = ClassBooking::where('user_id', Auth::id())
                ->where('class_schedule_id', $id)
                ->where('booking_status', 'confirmed')
                ->first();

            // Check user quota for this class type
            $this->checkUserQuota();
        }
    }

    private function initializeReformerPositions()
    {
        // Initialize all 8 positions as available
        $this->available_positions = collect(range(1, 8))->map(function ($position) {
            return [
                'position' => $position,
                'is_available' => true,
                'booked_by' => null
            ];
        })->toArray();

        // Get existing bookings for this schedule to mark occupied positions
        $existingBookings = ClassBooking::where('class_schedule_id', $this->id)
            ->where('booking_status', 'confirmed')
            ->whereNotNull('reformer_position')
            ->get();

        foreach ($existingBookings as $booking) {
            $position = $booking->reformer_position;
            if ($position >= 1 && $position <= 8) {
                $this->available_positions[$position - 1]['is_available'] = false;
                $this->available_positions[$position - 1]['booked_by'] = $booking->user->name ?? 'Unknown';
            }
        }
    }

    public function selectPosition($position)
    {
        if ($this->is_reformer_class && $this->available_positions[$position - 1]['is_available']) {
            $this->selected_position = $position;
        }
    }

    private function checkUserQuota()
    {
        $groupClassId = $this->schedule->classes->group_class_id;

        // First check for combination package quota (class_id = 0)
        $combinationQuota = UserKuota::where('user_id', Auth::id())
            ->where('class_id', 0) // Shared quota for combination packages
            ->where('end_date', '>', now())
            ->sum('kuota');

        if ($combinationQuota > 0) {
            // User has combination package quota
            $this->cek_quota = $combinationQuota;
            return;
        }

        // Check specific class type quota
        $specificQuota = UserKuota::where('user_id', Auth::id())
            ->where('class_id', $groupClassId)
            ->where('end_date', '>', now())
            ->sum('kuota');

        $this->cek_quota = $specificQuota;
    }

    public function save()
    {

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'id' => 'required',
        ]);

        // For Reformer class, validate position selection
        if ($this->is_reformer_class && !$this->selected_position) {
            session()->flash('error', 'Please select a Reformer position before booking.');
            return;
        }

        // Check if schedule exists and is bookable
        if (!$this->schedule->canBeBooked()) {
            session()->flash('error', 'This class cannot be booked. Booking closes 1 hour before class starts.');
            return;
        }

        // Check if user already has booking
        if ($this->existing_booking) {
            session()->flash('error', 'You have already booked this class.');
            return;
        }

        // Check quota
        if ($this->cek_quota <= 0) {
            session()->flash('error', 'You do not have enough quota for this class type.');
            return;
        }

        // Check class capacity
        if ($this->schedule->isFull()) {
            session()->flash('error', 'This class is fully booked.');
            return;
        }

        // For Reformer class, check if selected position is still available
        if ($this->is_reformer_class) {
            $positionTaken = ClassBooking::where('class_schedule_id', $this->id)
                ->where('reformer_position', $this->selected_position)
                ->where('booking_status', 'confirmed')
                ->exists();

            if ($positionTaken) {
                session()->flash('error', 'Selected position is no longer available. Please choose another position.');
                $this->initializeReformerPositions(); // Refresh positions
                return;
            }
        }

        DB::beginTransaction();
        try {
            // Get user membership for this class type
            $userMembership = $this->getUserMembershipForClass();

            if (!$userMembership) {
                session()->flash('error', 'No valid membership found for this class type.');
                return;
            }

            // Create booking data
            $bookingData = [
                'user_id' => Auth::id(),
                'user_membership_id' => $userMembership->id,
                'class_schedule_id' => $this->id,
                'booking_status' => 'confirmed',
                'booked_at' => now(),
                'created_by_id' => Auth::id(),
            ];

            // Add Reformer position if applicable
            if ($this->is_reformer_class && $this->selected_position) {
                $bookingData['reformer_position'] = $this->selected_position;
            }

            // Create booking
            $booking = ClassBooking::create($bookingData);

            // Update class schedule capacity
            $this->schedule->increment('capacity_book');

            // Decrease user quota
            $this->decreaseUserQuota();

            DB::commit();

            // Send email to user
            $this->sendBookingConfirmationEmail($booking->id);

            session()->flash('success', 'Class booked successfully!');

            // Redirect to booking invoice page with the new booking ID
            return redirect()->route('user.my-bookings', ['bookingId' => $booking->id]);
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Failed to book class: ' . $e->getMessage());
        }
    }

    private function getUserMembershipForClass()
    {
        $groupClassId = $this->schedule->classes->group_class_id;

        // Get all active memberships for the user
        $activeMemberships = UserMembership::where('user_id', Auth::id())
            ->where('status', 'active')
            ->where('payment_status', 'paid')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->get();

        foreach ($activeMemberships as $membership) {
            // First check for combination package quota (class_id = 0)
            $combinationQuota = UserKuota::where('user_id', Auth::id())
                ->where('class_id', 0) // Shared quota
                ->where('invoice_number', $membership->invoice_number)
                ->where('kuota', '>', 0)
                ->where('end_date', '>', now())
                ->exists();

            if ($combinationQuota) {
                // Check if this combination membership includes the requested class type
                $includesClassType = $membership->membership->groupClasses()
                    ->where('class_memberships.class_id', $groupClassId)
                    ->exists();

                if ($includesClassType) {
                    return $membership;
                }
            }

            // Check specific class type quota
            $specificQuota = UserKuota::where('user_id', Auth::id())
                ->where('class_id', $groupClassId)
                ->where('invoice_number', $membership->invoice_number)
                ->where('kuota', '>', 0)
                ->where('end_date', '>', now())
                ->exists();

            if ($specificQuota) {
                return $membership;
            }
        }

        return null;
    }

    private function decreaseUserQuota()
    {
        $groupClassId = $this->schedule->classes->group_class_id;

        // First try to decrease combination package quota (class_id = 0)
        $combinationQuota = UserKuota::where('user_id', Auth::id())
            ->where('class_id', 0) // Shared quota for combination packages
            ->where('end_date', '>', now())
            ->where('kuota', '>', 0)
            ->orderBy('end_date', 'asc')
            ->first();

        if ($combinationQuota) {
            $combinationQuota->decrement('kuota');
            return;
        }

        // If no combination quota, decrease specific class type quota
        $specificQuota = UserKuota::where('user_id', Auth::id())
            ->where('class_id', $groupClassId)
            ->where('end_date', '>', now())
            ->where('kuota', '>', 0)
            ->orderBy('end_date', 'asc')
            ->first();

        if ($specificQuota) {
            $specificQuota->decrement('kuota');
        }
    }

    /**
     * Send booking confirmation email to user
     */
    private function sendBookingConfirmationEmail($bookingId)
    {
        try {
            // Get booking with all necessary relations
            $booking = ClassBooking::with([
                'classSchedule.classes.groupClass',
                'classSchedule.trainer',
                'userMembership.membership',
                'user'
            ])->find($bookingId);

            if (!$booking) {
                Log::error('Booking not found for email sending', ['booking_id' => $bookingId]);
                return;
            }

            // Prepare booking data for email template
            $bookingData = [
                'id' => $booking->id,
                'class_name' => $booking->classSchedule->classes->name,
                'group_class' => $booking->classSchedule->classes->groupClass->name,
                'trainer_name' => $booking->classSchedule->trainer->name,
                'date' => $booking->classSchedule->start_time->format('Y-m-d'),
                'start_time' => $booking->classSchedule->start_time->format('H:i'),
                'end_time' => $booking->classSchedule->end_time->format('H:i'),
                'booking_code' => $booking->booking_code,
                'qr_code' => $this->generateEmailQrCode($booking->booking_code),
                'membership_name' => $booking->userMembership->membership->name ?? 'N/A',
                'reformer_position' => $booking->reformer_position,
                'user_name' => $booking->user->name,
                'user_email' => $booking->user->email,
            ];

            // Send email
            Mail::to($booking->user->email)->send(new bookingInvoiceEmail($bookingData));

            Log::info('Booking confirmation email sent successfully', [
                'booking_id' => $bookingId,
                'user_email' => $booking->user->email,
                'class_name' => $bookingData['class_name']
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Don't throw exception to prevent booking process from failing
            // Email failure shouldn't stop the booking process
        }
    }

    /**
     * Generate QR code specifically for email (using online service for better compatibility)
     */
    private function generateEmailQrCode($bookingCode)
    {
        // Use online QR code service for better email client compatibility
        return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($bookingCode);
    }

    public function render()
    {
        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.public.checkout-class')->layout('components.layouts.website', [
            'title' => $menu->title ?? 'Book Class',
            'description' => $menu->description ?? 'Book your fitness class',
            'keywords' => $menu->keywords ?? 'fitness, class, booking',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}

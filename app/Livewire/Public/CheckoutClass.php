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
use Livewire\Component;
use Carbon\Carbon;

class CheckoutClass extends Component
{
    public $id, $schedule, $cek_quota;
    public $existing_booking;

    public function mount($id)
    {
        $this->id = $id;
        
        // Get class schedule with relations
        $this->schedule = ClassSchedules::with(['classes.groupClass', 'trainer'])->find($id);
        
        if (!$this->schedule) {
            abort(404, 'Schedule not found');
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

        DB::beginTransaction();
        try {
            // Get user membership for this class type
            $userMembership = $this->getUserMembershipForClass();
            
            if (!$userMembership) {
                session()->flash('error', 'No valid membership found for this class type.');
                return;
            }

            // Create booking
            $booking = ClassBooking::create([
                'user_id' => Auth::id(),
                'user_membership_id' => $userMembership->id,
                'class_schedule_id' => $this->id,
                'booking_status' => 'confirmed',
                'booked_at' => now(),
                'created_by_id' => Auth::id(),
            ]);

            // Update class schedule capacity
            $this->schedule->increment('capacity_book');

            // Decrease user quota
            $this->decreaseUserQuota();

            DB::commit();

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
            ->where(function($query) {
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

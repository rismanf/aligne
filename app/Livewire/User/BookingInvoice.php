<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\ClassBooking;
use App\Models\UserMembership;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingInvoice extends Component
{
    public $booking;
    public $bookingId;

    public function mount($bookingId = null)
    {
        // If no booking ID provided, get the latest booking
        if (!$bookingId) {
            $latestBooking = ClassBooking::where('user_id', Auth::id())
                ->where('booking_status', 'confirmed')
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($latestBooking) {
                $this->bookingId = $latestBooking->id;
            }
        } else {
            $this->bookingId = $bookingId;
        }

        $this->loadBooking();
    }

    public function loadBooking()
    {
        if (!Auth::check() || !$this->bookingId) {
            return;
        }

        $booking = ClassBooking::with(['classSchedule.classes.groupClass', 'classSchedule.trainer', 'userMembership'])
            ->where('id', $this->bookingId)
            ->where('user_id', Auth::id())
            ->first();

        if ($booking) {
            $this->booking = [
                'id' => $booking->id,
                'class_name' => $booking->classSchedule->classes->name,
                'group_class' => $booking->classSchedule->classes->groupClass->name,
                'trainer_name' => $booking->classSchedule->trainer->name,
                'date' => $booking->classSchedule->start_time->format('Y-m-d'),
                'start_time' => $booking->classSchedule->start_time->format('H:i'),
                'end_time' => $booking->classSchedule->end_time->format('H:i'),
                'booking_code' => $booking->booking_code,
                'qr_code' => $booking->generateQrCode(),
                'can_cancel' => $booking->canBeCancelled(),
                'visit_status' => $booking->visit_status,
                'visited_at' => $booking->visited_at,
                'created_at' => $booking->created_at,
                'booking_status' => $booking->booking_status,
                'membership_name' => $booking->userMembership->membership->name ?? 'N/A',
            ];
        }
    }

    public function cancelBooking()
    {
        try {
            $booking = ClassBooking::findOrFail($this->bookingId);
            
            if ($booking->user_id !== Auth::id()) {
                session()->flash('error', 'You can only cancel your own bookings.');
                return;
            }

            if (!$booking->canBeCancelled()) {
                session()->flash('error', 'This booking cannot be cancelled. Cancellation is only allowed up to 12 hours before class starts.');
                return;
            }

            $booking->cancel();
            
            session()->flash('success', 'Booking cancelled successfully!');
            $this->loadBooking();

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to cancel booking: ' . $e->getMessage());
        }
    }

    private function generateQRCode($bookingCode)
    {
        // Generate QR code URL - you can use any QR code service
        // For example: https://api.qrserver.com/v1/create-qr-code/
        return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($bookingCode);
    }

    public function render()
    {
        return view('livewire.user.booking-invoice')->layout('components.layouts.website', [
            'title' => 'Booking Invoice',
            'description' => 'Your class booking confirmation and details',
            'keywords' => 'booking, invoice, class, confirmation',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}

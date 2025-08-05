<?php

namespace App\Livewire\User;

use App\Models\Menu;
use App\Models\ClassBooking;
use App\Models\UserKuota;
use Livewire\Component;
use Carbon\Carbon;

class Booking extends Component
{
    public $bookings = [];
    public $activeBookings = [];
    public $pastBookings = [];

    public function mount()
    {
        $this->loadBookings();
    }

    public function loadBookings()
    {
        // Get all bookings for the current user
        $allBookings = ClassBooking::with([
            'classSchedule.classes.groupClass',
            'classSchedule.trainer',
            'userMembership.membership'
        ])
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();

        $this->bookings = [];
        $this->activeBookings = [];
        $this->pastBookings = [];

        foreach ($allBookings as $booking) {
            $bookingData = [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'class_name' => $booking->classSchedule->classes->name ?? 'N/A',
                'group_class' => $booking->classSchedule->classes->groupClass->name ?? 'N/A',
                'date' => $booking->classSchedule->date ?? now(),
                'start_time' => $booking->classSchedule->start_time ?? '00:00',
                'end_time' => $booking->classSchedule->end_time ?? '00:00',
                'trainer_name' => $booking->classSchedule->trainer->name ?? 'N/A',
                'membership_name' => $booking->userMembership->membership->name ?? 'N/A',
                'booking_status' => $booking->booking_status,
                'visit_status' => $booking->visit_status,
                'created_at' => $booking->created_at,
                'can_cancel' => $this->canCancelBooking($booking),
            ];

            $this->bookings[] = $bookingData;

            // Separate active and past bookings
            try {
                $classDate = $booking->classSchedule->date ?? now()->format('Y-m-d');
                $startTime = $booking->classSchedule->start_time ?? '00:00:00';
                
                // Ensure proper format
                if (strlen($startTime) == 5) {
                    $startTime .= ':00';
                }
                
                $classDateTime = Carbon::parse($classDate . ' ' . $startTime);
                if ($classDateTime->isFuture()) {
                    $this->activeBookings[] = $bookingData;
                } else {
                    $this->pastBookings[] = $bookingData;
                }
            } catch (\Exception $e) {
                // If date parsing fails, put in past bookings as fallback
                $this->pastBookings[] = $bookingData;
            }
        }
    }

    private function canCancelBooking($booking)
    {
        if ($booking->booking_status !== 'confirmed') {
            return false;
        }

        try {
            $classDate = $booking->classSchedule->date ?? now()->format('Y-m-d');
            $startTime = $booking->classSchedule->start_time ?? '00:00:00';
            
            // Ensure proper format
            if (strlen($startTime) == 5) {
                $startTime .= ':00';
            }
            
            $classDateTime = Carbon::parse($classDate . ' ' . $startTime);
            $cancelDeadline = $classDateTime->copy()->subHours(12);

            return now()->isBefore($cancelDeadline);
        } catch (\Exception $e) {
            // If date parsing fails, don't allow cancellation
            return false;
        }
    }

    public function cancelBooking($bookingId)
    {
        $booking = ClassBooking::find($bookingId);
        
        if (!$booking || $booking->user_id !== auth()->id()) {
            session()->flash('error', 'Booking not found.');
            return;
        }

        if (!$this->canCancelBooking($booking)) {
            session()->flash('error', 'Cannot cancel booking. Cancellation deadline has passed.');
            return;
        }

        $booking->update([
            'booking_status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Decrease class capacity
        $booking->classSchedule->decrement('capacity_book');

        // Return quota to user
        $this->returnQuotaToUser($booking);

        session()->flash('success', 'Booking cancelled successfully.');
        $this->loadBookings(); // Refresh the bookings
    }

    private function returnQuotaToUser($booking)
    {
        $groupClassId = $booking->classSchedule->classes->group_class_id;
        $userMembership = $booking->userMembership;

        if (!$userMembership) {
            return;
        }

        // Check if this is a flexible quota package
        if ($userMembership->isFlexibleQuota()) {
            // Return quota to flexible package (class_id = 0)
            $flexibleQuota = UserKuota::where('user_id', $booking->user_id)
                ->where('class_id', 0)
                ->where('invoice_number', $userMembership->invoice_number)
                ->where('end_date', '>', now())
                ->first();

            if ($flexibleQuota) {
                $flexibleQuota->increment('kuota');
            }
        } else {
            // Return quota to specific class type
            $specificQuota = UserKuota::where('user_id', $booking->user_id)
                ->where('class_id', $groupClassId)
                ->where('invoice_number', $userMembership->invoice_number)
                ->where('end_date', '>', now())
                ->first();

            if ($specificQuota) {
                $specificQuota->increment('kuota');
            }
        }
    }

    public function render()
    {
        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.user.booking')->layout('components.layouts.website', [
            'title' => $menu->title ?? 'My Bookings',
            'description' => $menu->description ?? 'View your class bookings',
            'keywords' => $menu->keywords ?? 'bookings, classes',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}

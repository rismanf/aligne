<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\ClassBooking;
use Carbon\Carbon;

class BookingDetail extends Component
{
    public $bookingId;
    public $booking = null;
    public $scanResult = null;
    public $message = '';
    public $messageType = '';

    public function mount($bookingId)
    {
        $this->bookingId = $bookingId;
        $this->loadBookingDetails();
    }

    public function loadBookingDetails()
    {
        try {
            $this->booking = ClassBooking::with(['user', 'classSchedule.classes.groupClass', 'classSchedule.trainer'])
                ->where('id', $this->bookingId)
                ->first();

            if (!$this->booking) {
                $this->setMessage('Booking not found.', 'error');
                return;
            }

            // Check if booking can be checked in
            if (!$this->booking->canCheckIn()) {
                if ($this->booking->booking_status === 'cancelled') {
                    $this->setMessage('This booking has been cancelled.', 'error');
                } elseif ($this->booking->visit_status === 'visited') {
                    $this->setMessage('This booking has already been checked in at ' . 
                        $this->booking->visited_at->format('M j, Y H:i'), 'warning');
                } else {
                    $classDate = $this->booking->classSchedule->start_time->toDateString();
                    $today = Carbon::today()->toDateString();
                    
                    if ($classDate !== $today) {
                        $this->setMessage('This booking is not for today. Class date: ' . 
                            $this->booking->classSchedule->start_time->format('M j, Y'), 'error');
                    } else {
                        $classStartTime = $this->booking->classSchedule->start_time;
                        $now = Carbon::now();
                        $checkInStart = $classStartTime->copy()->subMinutes(30);
                        $checkInEnd = $classStartTime->copy()->addMinutes(10);

                        if ($now->lt($checkInStart)) {
                            $this->setMessage('Check-in opens 30 minutes before class starts (' . 
                                $checkInStart->format('H:i') . ').', 'warning');
                        } elseif ($now->gt($checkInEnd)) {
                            $this->setMessage('Check-in window has closed. Class started at ' . 
                                $classStartTime->format('H:i') . '.', 'error');
                        } else {
                            $this->setMessage('Unable to check in at this time. Please contact staff for assistance.', 'error');
                        }
                    }
                }
            } else {
                $this->setMessage('Booking found! Please confirm your check-in.', 'success');
            }

            // Get member statistics
            $memberSince = $this->booking->user->created_at;
            $completedClasses = ClassBooking::where('user_id', $this->booking->user_id)
                ->where('visit_status', 'visited')
                ->count();

            // Prepare booking details
            $this->scanResult = [
                'user_name' => $this->booking->user->name,
                'class_name' => $this->booking->classSchedule->classes->name,
                'group_class' => $this->booking->classSchedule->classes->groupClass->name,
                'trainer_name' => $this->booking->classSchedule->trainer->name,
                'class_time' => $this->booking->classSchedule->start_time->format('H:i'),
                'booking_code' => $this->booking->booking_code,
                'reformer_position' => $this->booking->reformer_position,
                'is_reformer_class' => $this->booking->classSchedule->classes->groupClass->name === 'REFORMER',
                'qr_verified' => true, // Since we're accessing via URL, assume QR was used
                'member_since' => $memberSince->format('M j, Y'),
                'completed_classes' => $completedClasses,
                'member_duration' => $memberSince->diffForHumans(),
            ];

        } catch (\Exception $e) {
            $this->setMessage('Error loading booking details: ' . $e->getMessage(), 'error');
        }
    }

    public function confirmCheckIn()
    {
        if (!$this->booking) {
            $this->setMessage('No booking selected.', 'error');
            return;
        }

        if (!$this->booking->canCheckIn()) {
            $this->setMessage('This booking cannot be checked in at this time.', 'error');
            return;
        }

        try {
            $this->booking->update([
                'visit_status' => 'visited',
                'visited_at' => Carbon::now(),
            ]);

            $this->setMessage('Check-in successful! Welcome ' . $this->booking->user->name . '! Enjoy your class!', 'success');
            
            // Redirect back to scanner after 3 seconds
            $this->dispatch('checkin-success');

        } catch (\Exception $e) {
            $this->setMessage('Error confirming check-in: ' . $e->getMessage(), 'error');
        }
    }

    public function backToScanner()
    {
        return redirect()->route('member-checkin');
    }

    private function setMessage($message, $type)
    {
        $this->message = $message;
        $this->messageType = $type;
    }

    public function render()
    {
        return view('livewire.public.booking-detail')->layout('components.layouts.checkin');
    }
}

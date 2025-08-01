<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ClassBooking;
use Carbon\Carbon;

class QRScanner extends Component
{
    public $bookingCode = '';
    public $scanResult = null;
    public $booking = null;
    public $message = '';
    public $messageType = '';

    public function scanBooking()
    {
        $this->reset(['scanResult', 'booking', 'message', 'messageType']);

        if (empty($this->bookingCode)) {
            $this->setMessage('Please enter a booking code.', 'error');
            return;
        }

        try {
            $this->booking = ClassBooking::with(['user', 'classSchedule.classes.groupClass', 'classSchedule.trainer'])
                ->where('booking_code', $this->bookingCode)
                ->first();

            if (!$this->booking) {
                $this->setMessage('Booking not found. Please check the code.', 'error');
                return;
            }

            // Check if booking is cancelled
            if ($this->booking->booking_status === 'cancelled') {
                $this->setMessage('This booking has been cancelled.', 'error');
                return;
            }

            // Check if already visited
            if ($this->booking->visit_status === 'visited') {
                $this->setMessage('This booking has already been checked in at ' . 
                    $this->booking->visited_at->format('M j, Y H:i'), 'warning');
                return;
            }

            // Check if class date is today
            $classDate = $this->booking->classSchedule->start_time->toDateString();
            $today = Carbon::today()->toDateString();

            if ($classDate !== $today) {
                $this->setMessage('This booking is not for today. Class date: ' . 
                    $this->booking->classSchedule->start_time->format('M j, Y'), 'error');
                return;
            }

            // Check if within check-in window (30 minutes before to 15 minutes after class starts)
            $classStartTime = $this->booking->classSchedule->start_time;
            $now = Carbon::now();
            $checkInStart = $classStartTime->copy()->subMinutes(30);
            $checkInEnd = $classStartTime->copy()->addMinutes(15);

            if ($now->lt($checkInStart)) {
                $this->setMessage('Check-in opens 30 minutes before class starts (' . 
                    $checkInStart->format('H:i') . ').', 'warning');
                return;
            }

            if ($now->gt($checkInEnd)) {
                $this->setMessage('Check-in window has closed. Class started at ' . 
                    $classStartTime->format('H:i') . '.', 'error');
                return;
            }

            // All checks passed - show booking details for confirmation
            $this->scanResult = [
                'user_name' => $this->booking->user->name,
                'class_name' => $this->booking->classSchedule->classes->name,
                'group_class' => $this->booking->classSchedule->classes->groupClass->name,
                'trainer_name' => $this->booking->classSchedule->trainer->name,
                'class_time' => $classStartTime->format('H:i'),
                'booking_code' => $this->booking->booking_code,
            ];

            $this->setMessage('Booking found! Please confirm check-in.', 'success');

        } catch (\Exception $e) {
            $this->setMessage('Error scanning booking: ' . $e->getMessage(), 'error');
        }
    }

    public function confirmCheckIn()
    {
        if (!$this->booking) {
            $this->setMessage('No booking selected.', 'error');
            return;
        }

        try {
            $this->booking->update([
                'visit_status' => 'visited',
                'visited_at' => Carbon::now(),
            ]);

            $this->setMessage('Check-in successful! Welcome ' . $this->booking->user->name . '!', 'success');
            
            // Reset for next scan
            $this->reset(['bookingCode', 'scanResult', 'booking']);

        } catch (\Exception $e) {
            $this->setMessage('Error confirming check-in: ' . $e->getMessage(), 'error');
        }
    }

    public function resetScan()
    {
        $this->reset(['bookingCode', 'scanResult', 'booking', 'message', 'messageType']);
    }

    private function setMessage($message, $type)
    {
        $this->message = $message;
        $this->messageType = $type;
    }

    public function render()
    {
        return view('livewire.admin.qr-scanner');
    }
}

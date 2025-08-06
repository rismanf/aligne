<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\ClassBooking;
use Carbon\Carbon;

class MemberCheckin extends Component
{
    public $bookingCode = '';
    public $scanResult = null;
    public $booking = null;
    public $message = '';
    public $messageType = '';
    public $showCamera = false;
    public $currentView = 'scan'; // 'scan' or 'detail'

    public function scanBooking()
    {
        $this->reset(['scanResult', 'booking', 'message', 'messageType']);

        if (empty($this->bookingCode)) {
            $this->setMessage('Please enter a booking code or scan QR code.', 'error');
            return;
        }

        try {
            // Try to parse JSON data first (from QR code)
            $qrData = json_decode($this->bookingCode, true);
            
            if ($qrData && isset($qrData['booking_code'])) {
                // QR code data format
                $bookingCode = $qrData['booking_code'];
                $qrToken = $qrData['token'] ?? null;
            } else {
                // Plain booking code
                $bookingCode = $this->bookingCode;
                $qrToken = null;
            }

            $this->booking = ClassBooking::with(['user', 'classSchedule.classes.groupClass', 'classSchedule.trainer'])
                ->where('booking_code', $bookingCode)
                ->first();

            if (!$this->booking) {
                $this->setMessage('Booking not found. Please check the code.', 'error');
                return;
            }

            // Verify QR token if provided
            if ($qrToken && !$this->booking->verifyQrToken($qrToken)) {
                $this->setMessage('Invalid QR code. Please use a valid QR code.', 'error');
                return;
            }

            // Use the model's canCheckIn method for validation
            if (!$this->booking->canCheckIn()) {
                // Determine specific reason
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
                return;
            }

            // All checks passed - redirect to booking detail page
            return redirect()->route('booking-detail', ['bookingId' => $this->booking->id]);

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

            $this->setMessage('Check-in successful! Welcome ' . $this->booking->user->name . '! Enjoy your class!', 'success');
            
            // Reset for next scan after a delay
            $this->dispatch('checkin-success');
            
            // Reset after 3 seconds and return to scan view
            $this->dispatch('reset-after-delay');

        } catch (\Exception $e) {
            $this->setMessage('Error confirming check-in: ' . $e->getMessage(), 'error');
        }
    }

    public function resetScan()
    {
        $this->reset(['bookingCode', 'scanResult', 'booking', 'message', 'messageType']);
        $this->showCamera = false;
        $this->currentView = 'scan';
    }

    public function backToScan()
    {
        $this->reset(['bookingCode', 'scanResult', 'booking', 'message', 'messageType']);
        $this->showCamera = false;
        $this->currentView = 'scan';
    }

    public function toggleCamera()
    {
        $this->showCamera = !$this->showCamera;
        if ($this->showCamera) {
            $this->dispatch('start-camera');
        } else {
            $this->dispatch('stop-camera');
        }
    }

    public function processQrCode($qrData)
    {
        $this->bookingCode = $qrData;
        $this->showCamera = false;
        $this->dispatch('stop-camera');
        $this->scanBooking();
    }

    private function setMessage($message, $type)
    {
        $this->message = $message;
        $this->messageType = $type;
    }

    public function render()
    {
        return view('livewire.public.member-checkin')->layout('components.layouts.checkin');
    }
}

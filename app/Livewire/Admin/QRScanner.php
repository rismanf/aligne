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
                        $checkInStart = $classStartTime->copy()->subMinutes(120);
                        $checkInEnd = $classStartTime->copy()->addMinutes(15);

                        if ($now->lt($checkInStart)) {
                            $this->setMessage('Check-in opens 120 minutes before class starts (' . 
                                $checkInStart->format('H:i') . ').', 'warning');
                        } elseif ($now->gt($checkInEnd)) {
                            $this->setMessage('Check-in window has closed. Class started at ' . 
                                $classStartTime->format('H:i') . '.', 'error');
                        } else {
                            // Get debug info for troubleshooting
                            $debugInfo = $this->booking->getCheckInWindowInfo();
                            $this->setMessage('Debug Info - Now: ' . $debugInfo['now'] . 
                                ', Class: ' . $debugInfo['class_start'] . 
                                ', Window: ' . $debugInfo['check_in_start'] . ' to ' . $debugInfo['check_in_end'] . 
                                ', Status: ' . $debugInfo['booking_status'] . 
                                ', Visit: ' . $debugInfo['visit_status'] . 
                                ', Can Check In: ' . ($debugInfo['can_check_in'] ? 'YES' : 'NO'), 'error');
                        }
                    }
                }
                return;
            }

            // All checks passed - show booking details for confirmation
            $this->scanResult = [
                'user_name' => $this->booking->user->name,
                'class_name' => $this->booking->classSchedule->classes->name,
                'group_class' => $this->booking->classSchedule->classes->groupClass->name,
                'trainer_name' => $this->booking->classSchedule->trainer->name,
                'class_time' => $this->booking->classSchedule->start_time->format('H:i'),
                'booking_code' => $this->booking->booking_code,
                'reformer_position' => $this->booking->reformer_position,
                'is_reformer_class' => $this->booking->classSchedule->classes->groupClass->name === 'REFORMER',
                'qr_verified' => $qrToken ? true : false,
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
        return view('livewire.admin.qr-scanner')->layout('components.layouts.app', [
            'breadcrumbs' => [
                ['link' => route("admin.home"), 'label' => 'Home', 'icon' => 's-home'],
                ['label' => 'Visit'],
            ],
            'title' => 'Visit Management',
        ]);
    }
}

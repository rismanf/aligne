<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class ClassBooking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'user_membership_id',
        'class_schedule_id',
        'booking_status',
        'booking_code',
        'qr_token',
        'visit_status',
        'visited_at',
        'booked_at',
        'cancelled_at',
        'created_by_id',
        'updated_by_id',
    ];

    protected $casts = [
        'booked_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'visited_at' => 'datetime',
    ];

    /**
     * Get the user who made the booking
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user membership used for this booking
     */
    public function userMembership()
    {
        return $this->belongsTo(UserMembership::class);
    }

    /**
     * Get the class schedule
     */
    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedules::class);
    }

    /**
     * Get the feedback for this booking
     */
    public function feedback()
    {
        return $this->hasOne(ClassFeedback::class);
    }

    /**
     * Check if booking is active
     */
    public function isActive()
    {
        return $this->booking_status === 'confirmed';
    }

    /**
     * Check if booking is cancelled
     */
    public function isCancelled()
    {
        return $this->booking_status === 'cancelled';
    }

    /**
     * Check if this booking can be cancelled (12 hours before class)
     */
    public function canBeCancelled()
    {
        if ($this->booking_status === 'cancelled') {
            return false;
        }

        $now = \Carbon\Carbon::now();
        $classStartTime = $this->classSchedule->full_start_date_time;
        
        // Can cancel up to 12 hours before class starts
        return $now->diffInHours($classStartTime, false) >= 12;
    }

    /**
     * Cancel the booking
     */
    public function cancel($reason = null)
    {
        if (!$this->canBeCancelled()) {
            throw new \Exception('Booking cannot be cancelled. Must be at least 12 hours before class starts.');
        }

        $this->update([
            'booking_status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Return quota to user
        $this->returnQuotaToUser();
        
        // Decrease capacity_book in class schedule
        $this->classSchedule->decrement('capacity_book');

        return true;
    }

    /**
     * Return quota to user when booking is cancelled
     */
    private function returnQuotaToUser()
    {
        $classId = $this->classSchedule->class_id;
        
        $userQuota = UserKuota::where('user_id', $this->user_id)
            ->where('class_id', $classId)
            ->where('end_date', '>', now())
            ->first();

        if ($userQuota) {
            $userQuota->increment('kuota');
        }
    }

    /**
     * Get time until class starts
     */
    public function getTimeUntilClassAttribute()
    {
        return \Carbon\Carbon::now()->diffForHumans($this->classSchedule->full_start_date_time, true);
    }

    /**
     * Check if booking is for upcoming class
     */
    public function getIsUpcomingAttribute()
    {
        return $this->classSchedule->full_start_date_time > \Carbon\Carbon::now();
    }

    /**
     * Scope for active bookings
     */
    public function scopeActive($query)
    {
        return $query->where('booking_status', 'confirmed');
    }

    /**
     * Scope for cancelled bookings
     */
    public function scopeCancelled($query)
    {
        return $query->where('booking_status', 'cancelled');
    }

    /**
     * Generate unique booking code
     */
    public static function generateBookingCode()
    {
        do {
            $code = 'BK' . strtoupper(substr(uniqid(), -8));
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }

    /**
     * Generate unique QR token
     */
    public static function generateQrToken()
    {
        do {
            $token = bin2hex(random_bytes(16));
        } while (self::where('qr_token', $token)->exists());

        return $token;
    }

    /**
     * Generate QR code for this booking
     */
    public function generateQrCode()
    {
        if (empty($this->qr_token)) {
            $this->update(['qr_token' => self::generateQrToken()]);
        }

        // Create QR code data - just use booking code for simplicity
        $qrData = $this->booking_code;

        try {
            $qrCode = new QrCode($qrData);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            return $result->getDataUri();
        } catch (\Exception $e) {
            // Fallback: return a simple data URL with booking code
            return 'data:text/plain;base64,' . base64_encode($this->booking_code);
        }
    }

    /**
     * Get QR code data for scanning
     */
    public function getQrData()
    {
        return [
            'booking_code' => $this->booking_code,
            'token' => $this->qr_token,
            'user_id' => $this->user_id,
            'class_schedule_id' => $this->class_schedule_id,
            'timestamp' => now()->timestamp
        ];
    }

    /**
     * Verify QR token
     */
    public function verifyQrToken($token)
    {
        return $this->qr_token === $token;
    }

    /**
     * Check if booking can be checked in
     */
    public function canCheckIn()
    {
        if ($this->booking_status !== 'confirmed') {
            return false;
        }

        if ($this->visit_status === 'visited') {
            return false;
        }

        // Check if class is today
        $classDate = $this->classSchedule->start_time->toDateString();
        $today = now()->toDateString();

        if ($classDate !== $today) {
            return false;
        }

        // Check if within check-in window (2 hours before to 15 minutes after)
        $classStartTime = $this->classSchedule->start_time;
        $now = now();
        $checkInStart = $classStartTime->copy()->subMinutes(120); // 2 hours before
        $checkInEnd = $classStartTime->copy()->addMinutes(15);

        return $now->between($checkInStart, $checkInEnd);
    }

    /**
     * Get check-in window info for debugging
     */
    public function getCheckInWindowInfo()
    {
        $classStartTime = $this->classSchedule->start_time;
        $now = now();
        $checkInStart = $classStartTime->copy()->subMinutes(120);
        $checkInEnd = $classStartTime->copy()->addMinutes(15);

        return [
            'now' => $now->format('Y-m-d H:i:s'),
            'class_start' => $classStartTime->format('Y-m-d H:i:s'),
            'check_in_start' => $checkInStart->format('Y-m-d H:i:s'),
            'check_in_end' => $checkInEnd->format('Y-m-d H:i:s'),
            'can_check_in' => $now->between($checkInStart, $checkInEnd),
            'booking_status' => $this->booking_status,
            'visit_status' => $this->visit_status,
            'class_date' => $this->classSchedule->start_time->toDateString(),
            'today' => now()->toDateString(),
        ];
    }

    /**
     * Check if this booking can receive feedback
     */
    public function canGiveFeedback()
    {
        // Must be visited and class must be completed
        if ($this->visit_status !== 'visited') {
            return false;
        }

        // Class must be completed (ended)
        $classEndTime = $this->classSchedule->end_time;
        if (now()->lt($classEndTime)) {
            return false;
        }

        // Check if feedback already exists
        if ($this->feedback()->exists()) {
            return false;
        }

        // Allow feedback up to 7 days after class
        $feedbackDeadline = $classEndTime->copy()->addDays(7);
        return now()->lte($feedbackDeadline);
    }

    /**
     * Check if feedback already submitted
     */
    public function hasFeedback()
    {
        return $this->feedback()->exists();
    }

    /**
     * Get feedback deadline
     */
    public function getFeedbackDeadline()
    {
        return $this->classSchedule->end_time->copy()->addDays(7);
    }

    /**
     * Boot method to auto-generate booking code and QR token
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = self::generateBookingCode();
            }
            if (empty($booking->qr_token)) {
                $booking->qr_token = self::generateQrToken();
            }
        });
    }
}

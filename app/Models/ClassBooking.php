<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassBooking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'user_membership_id',
        'class_schedule_id',
        'booking_status',
        'booking_code',
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
     * Boot method to auto-generate booking code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = self::generateBookingCode();
            }
        });
    }
}

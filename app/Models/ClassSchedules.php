<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClassSchedules extends Model
{
    protected $fillable = [
        'name',
        'class_id',
        'trainer_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'capacity',
        'capacity_book',
        'is_active',
        'created_by_id',
        'updated_by_id'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id')->withTrashed();
    }

    public function bookings()
    {
        return $this->hasMany(ClassBooking::class, 'class_schedule_id');
    }

    // Business Logic Methods
    
    /**
     * Check if class can be booked (minimum 1 hour before start time)
     */
    public function canBeBooked()
    {
        $now = Carbon::now();
        
        // Must be at least 1 hour before class starts
        return $now->diffInHours($this->start_time, false) >= 1;
    }

    /**
     * Check if booking can be cancelled (maximum 12 hours before start time)
     */
    public function canBeCancelled()
    {
        $now = Carbon::now();
        
        // Can cancel up to 12 hours before class starts
        return $now->diffInHours($this->start_time, false) >= 12;
    }

    /**
     * Check if class is full
     */
    public function isFull()
    {
        return $this->capacity_book >= $this->capacity;
    }

    /**
     * Get available spots
     */
    public function getAvailableSpotsAttribute()
    {
        return $this->capacity - $this->capacity_book;
    }

    /**
     * Get formatted start time
     */
    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time->format('H:i');
    }

    /**
     * Get formatted end time
     */
    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time->format('H:i');
    }

    /**
     * Get full datetime for start
     */
    public function getFullStartDateTimeAttribute()
    {
        return $this->start_time;
    }

    /**
     * Scope for available classes (can be booked)
     */
    public function scopeAvailableForBooking($query)
    {
        $oneHourFromNow = Carbon::now()->addHour();
        
        return $query->where('is_active', true)
                    ->where('start_time', '>', $oneHourFromNow)
                    ->whereColumn('capacity_book', '<', 'capacity');
    }

    /**
     * Scope for today's classes
     */
    public function scopeToday($query)
    {
        return $query->whereDate('start_time', Carbon::today());
    }

    /**
     * Scope for upcoming classes
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>=', Carbon::today());
    }
}

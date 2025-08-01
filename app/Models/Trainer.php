<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trainer extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * Get the class schedules for the trainer
     */
    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class, 'trainer_id');
    }

    /**
     * Get the classes taught by this trainer
     */
    public function classes()
    {
        return $this->hasManyThrough(Classes::class, ClassSchedule::class, 'trainer_id', 'id', 'id', 'class_id');
    }

    /**
     * Get the bookings for this trainer's classes
     */
    public function bookings()
    {
        return $this->hasManyThrough(ClassBooking::class, ClassSchedule::class, 'trainer_id', 'class_schedule_id');
    }
}

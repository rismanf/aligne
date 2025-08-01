<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassFeedback extends Model
{
    use SoftDeletes;

    protected $table = 'class_feedback';

    protected $fillable = [
        'class_booking_id',
        'user_id',
        'class_schedule_id',
        'rating',
        'comment',
        'aspects',
        'recommend',
        'is_anonymous',
        'is_approved',
        'submitted_at',
    ];

    protected $casts = [
        'aspects' => 'array',
        'recommend' => 'boolean',
        'is_anonymous' => 'boolean',
        'is_approved' => 'boolean',
        'submitted_at' => 'datetime',
        'rating' => 'integer',
    ];

    /**
     * Get the booking this feedback belongs to
     */
    public function classBooking()
    {
        return $this->belongsTo(ClassBooking::class);
    }

    /**
     * Get the user who submitted the feedback
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the class schedule this feedback is for
     */
    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedules::class);
    }

    /**
     * Get the class details through schedule
     */
    public function class()
    {
        return $this->hasOneThrough(Classes::class, ClassSchedules::class, 'id', 'id', 'class_schedule_id', 'class_id');
    }

    /**
     * Get the trainer through schedule
     */
    public function trainer()
    {
        return $this->hasOneThrough(Trainer::class, ClassSchedules::class, 'id', 'id', 'class_schedule_id', 'trainer_id');
    }

    /**
     * Scope for approved feedback
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for public feedback (not anonymous)
     */
    public function scopePublic($query)
    {
        return $query->where('is_anonymous', false);
    }

    /**
     * Scope for high ratings (4-5 stars)
     */
    public function scopeHighRating($query)
    {
        return $query->where('rating', '>=', 4);
    }

    /**
     * Get rating as stars
     */
    public function getRatingStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Get user display name (anonymous or real name)
     */
    public function getUserDisplayNameAttribute()
    {
        if ($this->is_anonymous) {
            return 'Anonymous Member';
        }
        
        return $this->user->name ?? 'Unknown User';
    }

    /**
     * Get formatted aspects rating
     */
    public function getFormattedAspectsAttribute()
    {
        if (!$this->aspects) {
            return [];
        }

        $aspectLabels = [
            'instructor' => 'Instructor Quality',
            'facility' => 'Facility & Equipment',
            'cleanliness' => 'Cleanliness',
            'atmosphere' => 'Class Atmosphere',
            'difficulty' => 'Difficulty Level',
        ];

        $formatted = [];
        foreach ($this->aspects as $aspect => $rating) {
            $formatted[] = [
                'label' => $aspectLabels[$aspect] ?? ucfirst($aspect),
                'rating' => $rating,
                'stars' => str_repeat('★', $rating) . str_repeat('☆', 5 - $rating)
            ];
        }

        return $formatted;
    }

    /**
     * Get average rating for a specific class
     */
    public static function getAverageRatingForClass($classId)
    {
        return self::whereHas('classSchedule', function($query) use ($classId) {
            $query->where('class_id', $classId);
        })->approved()->avg('rating');
    }

    /**
     * Get average rating for a specific trainer
     */
    public static function getAverageRatingForTrainer($trainerId)
    {
        return self::whereHas('classSchedule', function($query) use ($trainerId) {
            $query->where('trainer_id', $trainerId);
        })->approved()->avg('rating');
    }

    /**
     * Get feedback statistics
     */
    public static function getStatistics($filters = [])
    {
        $query = self::approved();

        if (isset($filters['class_id'])) {
            $query->whereHas('classSchedule', function($q) use ($filters) {
                $q->where('class_id', $filters['class_id']);
            });
        }

        if (isset($filters['trainer_id'])) {
            $query->whereHas('classSchedule', function($q) use ($filters) {
                $q->where('trainer_id', $filters['trainer_id']);
            });
        }

        if (isset($filters['date_from'])) {
            $query->where('submitted_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('submitted_at', '<=', $filters['date_to']);
        }

        return [
            'total_feedback' => $query->count(),
            'average_rating' => round($query->avg('rating'), 2),
            'rating_distribution' => $query->selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->orderBy('rating', 'desc')
                ->pluck('count', 'rating')
                ->toArray(),
            'recommendation_rate' => $query->where('recommend', true)->count() / max($query->count(), 1) * 100,
        ];
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($feedback) {
            if (empty($feedback->submitted_at)) {
                $feedback->submitted_at = now();
            }
        });
    }
}

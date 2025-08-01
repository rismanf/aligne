<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupClass extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name',
        'description',
        'category',
        'level',
        'image_original',
        'is_active',
        'created_by_id',
        'updated_by_id',
    ];

    // Class categories
    const CATEGORY_REFORMER = 'reformer';
    const CATEGORY_CHAIR = 'chair';
    const CATEGORY_FUNCTIONAL = 'functional';

    // Class levels
    const LEVEL_BEGINNER = 'beginner';
    const LEVEL_INTERMEDIATE = 'intermediate';
    const LEVEL_ADVANCED = 'advanced';

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get available categories
     */
    public static function getCategories()
    {
        return [
            self::CATEGORY_REFORMER => 'REFORMER CLASS',
            self::CATEGORY_CHAIR => 'CHAIR CLASS',
            self::CATEGORY_FUNCTIONAL => 'FUNCTIONAL CLASS',
        ];
    }

    /**
     * Get signature class categories (Reformer + Chair)
     */
    public static function getSignatureCategories()
    {
        return [
            self::CATEGORY_REFORMER => 'REFORMER CLASS',
            self::CATEGORY_CHAIR => 'CHAIR CLASS',
        ];
    }

    /**
     * Get reformer class names
     */
    public static function getReformerClasses()
    {
        return [
            'Chair Play',
            'Chairful Moves',
            'Balance Base',
        ];
    }

    /**
     * Get chair class names
     */
    public static function getChairClasses()
    {
        return [
            'Soul Stretch',
            'Melt & Reset',
            'Align & Release',
        ];
    }

    /**
     * Get available levels
     */
    public static function getLevels()
    {
        return [
            self::LEVEL_BEGINNER => 'Beginner',
            self::LEVEL_INTERMEDIATE => 'Intermediate',
            self::LEVEL_ADVANCED => 'Advanced',
        ];
    }

    /**
     * Get individual classes under this group
     */
    public function classes()
    {
        return $this->hasMany(Classes::class, 'group_class_id');
    }

    /**
     * Get active individual classes
     */
    public function activeClasses()
    {
        return $this->hasMany(Classes::class, 'group_class_id')
            ->where('is_active', true);
    }

    /**
     * Get class schedules through individual classes
     */
    public function schedules()
    {
        return $this->hasManyThrough(ClassSchedules::class, Classes::class, 'group_class_id', 'class_id');
    }

    /**
     * Get active schedules
     */
    public function activeSchedules()
    {
        return $this->hasManyThrough(ClassSchedules::class, Classes::class, 'group_class_id', 'class_id')
            ->where('class_schedules.is_active', true)
            ->where('class_schedules.start_time', '>', now());
    }

    /**
     * Get upcoming schedules for today and future
     */
    public function upcomingSchedules()
    {
        return $this->hasManyThrough(ClassSchedules::class, Classes::class, 'group_class_id', 'class_id')
            ->where('class_schedules.is_active', true)
            ->where('class_schedules.start_time', '>=', now())
            ->orderBy('class_schedules.start_time');
    }

    /**
     * Get memberships that include this class group
     */
    public function memberships()
    {
        return $this->belongsToMany(Product::class, 'class_memberships', 'class_id', 'membership_id')
            ->withPivot('quota')
            ->withTimestamps();
    }

    /**
     * Check if this is a signature class (Reformer or Chair)
     */
    public function isSignatureClass()
    {
        return in_array($this->category, [self::CATEGORY_REFORMER, self::CATEGORY_CHAIR]);
    }

    /**
     * Check if this is a functional class
     */
    public function isFunctionalClass()
    {
        return $this->category === self::CATEGORY_FUNCTIONAL;
    }

    /**
     * Scope for signature classes (Reformer + Chair)
     */
    public function scopeSignatureClasses($query)
    {
        return $query->whereIn('category', [self::CATEGORY_REFORMER, self::CATEGORY_CHAIR]);
    }

    /**
     * Scope for functional classes
     */
    public function scopeFunctionalClasses($query)
    {
        return $query->where('category', self::CATEGORY_FUNCTIONAL);
    }

    /**
     * Scope for specific category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for specific level
     */
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope for active classes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for classes available for booking (has upcoming schedules)
     */
    public function scopeAvailableForBooking($query)
    {
        return $query->whereHas('activeSchedules');
    }

    /**
     * Get formatted category name
     */
    public function getCategoryNameAttribute()
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? $this->category;
    }

    /**
     * Get formatted level name
     */
    public function getLevelNameAttribute()
    {
        $levels = self::getLevels();
        return $levels[$this->level] ?? $this->level;
    }

    /**
     * Get category badge color for UI
     */
    public function getCategoryBadgeColorAttribute()
    {
        switch ($this->category) {
            case self::CATEGORY_REFORMER:
                return 'bg-blue-100 text-blue-800';
            case self::CATEGORY_CHAIR:
                return 'bg-green-100 text-green-800';
            case self::CATEGORY_FUNCTIONAL:
                return 'bg-purple-100 text-purple-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    /**
     * Get next available schedule
     */
    public function getNextScheduleAttribute()
    {
        return $this->upcomingSchedules()->first();
    }

    /**
     * Get total available spots for upcoming schedules
     */
    public function getTotalAvailableSpotsAttribute()
    {
        return $this->upcomingSchedules()
            ->selectRaw('SUM(capacity - capacity_book) as total_spots')
            ->value('total_spots') ?? 0;
    }
}

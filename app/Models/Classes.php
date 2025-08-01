<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'group_class_id',
        'group_class',
        'name',
        'time',
        'level_class_id',
        'level_class',
        'mood_class_id',
        'mood_class',
        'description',
        'image_original',
        'is_active',
        'created_by_id',
        'updated_by_id'
    ];

    protected $casts = [
        'time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    /**
     * Get the group class that this class belongs to
     */
    public function groupClass()
    {
        return $this->belongsTo(GroupClass::class, 'group_class_id');
    }

    /**
     * Get the class schedules for this class
     */
    public function schedules()
    {
        return $this->hasMany(ClassSchedules::class, 'class_id');
    }

    /**
     * Get the memberships that include this class
     */
    public function memberships()
    {
        return $this->belongsToMany(Membership::class, 'class_memberships', 'class_id', 'membership_id')
                    ->withPivot('quota')
                    ->withTimestamps();
    }

    /**
     * Get the user quotas for this class
     */
    public function userQuotas()
    {
        return $this->hasMany(UserKuota::class, 'class_id');
    }
}

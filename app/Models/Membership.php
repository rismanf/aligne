<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'class_quota',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user memberships for this membership
     */
    public function userMemberships()
    {
        return $this->hasMany(UserMembership::class);
    }

    /**
     * Get the classes associated with this membership
     */
    public function classes()
    {
        return $this->belongsToMany(GroupClass::class, 'class_memberships', 'membership_id', 'group_class_id');
    }
}

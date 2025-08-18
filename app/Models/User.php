<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'title',
        'last_login_at',
        'last_login_ip',
        'email',
        'avatar',
        'password',
        'is_guest',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_guest' => 'boolean',
        ];
    }

    /**
     * Get user's memberships
     */
    public function memberships()
    {
        return $this->hasMany(UserMembership::class);
    }

    /**
     * Get user's memberships (alias for better readability)
     */
    public function userMemberships()
    {
        return $this->hasMany(UserMembership::class);
    }

    /**
     * Get user's active memberships
     */
    public function activeMemberships()
    {
        return $this->hasMany(UserMembership::class)
            ->where('payment_status', 'paid')
            ->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    /**
     * Get user's class bookings
     */
    public function classBookings()
    {
        return $this->hasMany(ClassBooking::class);
    }

    /**
     * Get user's quota records
     */
    public function quotas()
    {
        return $this->hasMany(UserKuota::class);
    }

    /**
     * Check if user has active membership for specific class type
     */
    public function hasActiveMembershipForClass($classId)
    {
        return $this->activeMemberships()
            ->whereHas('membership.classes', function($query) use ($classId) {
                $query->where('group_classes.id', $classId);
            })
            ->exists();
    }

    /**
     * Get remaining quota for specific class
     */
    public function getRemainingQuotaForClass($classId)
    {
        return $this->quotas()
            ->where('class_id', $classId)
            ->where('end_date', '>', now())
            ->sum('kuota');
    }

    /**
     * Check if user has any active membership
     */
    public function hasActiveMembership()
    {
        return $this->activeMemberships()->exists();
    }

    /**
     * Get user's active membership for specific class
     */
    public function getActiveMembershipForClass($classId)
    {
        return $this->activeMemberships()
            ->whereHas('membership.classes', function($query) use ($classId) {
                $query->where('group_classes.id', $classId);
            })
            ->first();
    }
}

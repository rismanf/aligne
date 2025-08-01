<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class UserMembership extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'membership_id',
        'unique_code',
        'price',
        'total_price',
        'payment_method',
        'payment_proof',
        'payment_status',
        'status',
        'starts_at',
        'expires_at',
        'paid_at',
        'confirmed_by',
        'confirmed_at',
        'created_by_id',
        'updated_by_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the membership
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the membership package
     */
    public function membership()
    {
        return $this->belongsTo(Product::class, 'membership_id');
    }

    /**
     * Get the admin who confirmed the payment
     */
    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    /**
     * Get class bookings for this membership
     */
    public function classBookings()
    {
        return $this->hasMany(ClassBooking::class);
    }

    /**
     * Get user quotas for this membership
     */
    public function quotas()
    {
        return $this->hasMany(UserKuota::class, 'user_id', 'user_id')
            ->where('invoice_number', $this->invoice_number);
    }

    /**
     * Get user quotas for this specific membership instance
     */
    public function membershipQuotas()
    {
        return $this->hasMany(UserKuota::class, 'user_id', 'user_id')
            ->where('invoice_number', $this->invoice_number)
            ->where('product_id', $this->membership_id);
    }

    /**
     * Check if membership is active
     */
    public function isActive()
    {
        return $this->payment_status === 'paid' 
            && $this->status === 'active' 
            && ($this->expires_at === null || $this->expires_at > now());
    }

    /**
     * Check if membership is expired
     */
    public function isExpired()
    {
        return $this->expires_at !== null && $this->expires_at <= now();
    }

    /**
     * Get remaining days
     */
    public function getRemainingDays()
    {
        if ($this->expires_at === null) {
            return null; // No expiration
        }
        
        if ($this->isExpired()) {
            return 0;
        }
        
        return now()->diffInDays($this->expires_at);
    }

    /**
     * Check if user can book a specific class type
     */
    public function canBookClassType($groupClassId)
    {
        if (!$this->isActive()) {
            return false;
        }

        // Check if this membership includes the requested class type
        return $this->membership->groupClasses()
            ->where('class_memberships.class_id', $groupClassId)
            ->exists();
    }

    /**
     * Get remaining quota for a specific class type
     */
    public function getRemainingQuotaForClass($groupClassId)
    {
        if (!$this->isActive()) {
            return 0;
        }

        return UserKuota::where('user_id', $this->user_id)
            ->where('class_id', $groupClassId)
            ->where('invoice_number', $this->invoice_number)
            ->where('end_date', '>', now())
            ->sum('kuota');
    }

    /**
     * Get all available class types for this membership
     */
    public function getAvailableClassTypes()
    {
        if (!$this->isActive()) {
            return collect();
        }

        return $this->membership->groupClasses()
            ->where('group_classes.is_active', true)
            ->get();
    }

    /**
     * Check if this is a signature class pack membership
     */
    public function isSignatureClassPack()
    {
        return $this->membership && $this->membership->isSignatureClassPack();
    }

    /**
     * Get membership type display name
     */
    public function getMembershipTypeAttribute()
    {
        if (!$this->membership) {
            return 'Unknown Package';
        }

        return $this->membership->package_display_name;
    }

    /**
     * Get formatted validity period
     */
    public function getValidityPeriodAttribute()
    {
        if ($this->expires_at === null) {
            return 'No expiration';
        }

        $remainingDays = $this->getRemainingDays();
        
        if ($remainingDays === 0) {
            return 'Expired';
        }

        return $remainingDays . ' days remaining';
    }

    /**
     * Activate membership after payment confirmation
     */
    public function activate()
    {
        $this->update([
            'payment_status' => 'paid',
            'status' => 'active',
            'starts_at' => now(),
            'confirmed_at' => now(),
            'confirmed_by' => auth()->id(),
        ]);

        // Create user quotas based on membership package
        $this->createUserQuotas();
    }

    /**
     * Create user quotas based on membership package
     */
    public function createUserQuotas()
    {
        $membership = $this->membership;
        if (!$membership) {
            return;
        }

        $classDetails = ClassMembership::where('membership_id', $this->membership_id)->get();

        $validUntil = $membership->valid_until 
            ? now()->addDays($membership->valid_until)->format('Y-m-d')
            : '9999-01-01';

        // Check if this is a combination package (like "Elevate Pack 8x Reformer / Chair Class")
        $isCombinationPackage = $this->isCombinationPackage();
        
        if ($isCombinationPackage) {
            // For combination packages, create a single shared quota entry
            // Use a special class_id of 0 to indicate shared quota across multiple class types
            
            // Fix for Elevate Pack: Extract the actual quota number from package name
            $actualQuota = $this->extractQuotaFromPackageName();
            
            // If we can't extract from name, fall back to the first class detail quota
            // (assuming all class details have the same quota for combination packages)
            if ($actualQuota === null && $classDetails->isNotEmpty()) {
                $actualQuota = $classDetails->first()->quota;
            }
            
            // Final fallback to sum if no other method works
            if ($actualQuota === null) {
                $actualQuota = $classDetails->sum('quota');
            }
            
            UserKuota::create([
                'user_id' => $this->user_id,
                'product_id' => $this->membership_id,
                'class_id' => 0, // Special ID for shared quota
                'kuota' => $actualQuota,
                'invoice_number' => $this->invoice_number,
                'start_date' => now(),
                'end_date' => $validUntil,
            ]);
        } else {
            // For specific class packages, create separate quotas
            foreach ($classDetails as $classDetail) {
                UserKuota::create([
                    'user_id' => $this->user_id,
                    'product_id' => $this->membership_id,
                    'class_id' => $classDetail->class_id,
                    'kuota' => $classDetail->quota,
                    'invoice_number' => $this->invoice_number,
                    'start_date' => now(),
                    'end_date' => $validUntil,
                ]);
            }
        }

        // Set expiration date
        $this->update([
            'expires_at' => $validUntil === '9999-01-01' ? null : $validUntil,
        ]);
    }

    /**
     * Check if this is a combination package that shares quota across multiple class types
     */
    public function isCombinationPackage()
    {
        if (!$this->membership) {
            return false;
        }

        // Check if membership name contains indicators of combination packages
        $membershipName = strtolower($this->membership->name);
        
        return str_contains($membershipName, 'reformer / chair') || 
               str_contains($membershipName, 'reformer/chair') ||
               str_contains($membershipName, 'elevate') ||
               str_contains($membershipName, 'signature class pack');
    }

    /**
     * Extract quota number from package name (e.g., "Elevate Pack 8x" -> 8)
     */
    private function extractQuotaFromPackageName()
    {
        if (!$this->membership) {
            return null;
        }

        $membershipName = $this->membership->name;
        
        // Pattern to match numbers followed by 'x' (e.g., "8x", "12x", "16x")
        if (preg_match('/(\d+)x/i', $membershipName, $matches)) {
            return (int) $matches[1];
        }
        
        // Pattern to match "Pack X" format (e.g., "Pack 8", "Pack 12")
        if (preg_match('/pack\s+(\d+)/i', $membershipName, $matches)) {
            return (int) $matches[1];
        }
        
        // Pattern to match standalone numbers in package names
        if (preg_match('/\b(\d+)\b/', $membershipName, $matches)) {
            $number = (int) $matches[1];
            // Only return reasonable quota numbers (between 1 and 50)
            if ($number >= 1 && $number <= 50) {
                return $number;
            }
        }
        
        return null;
    }

    /**
     * Get remaining quota for combination packages
     */
    public function getRemainingCombinationQuota()
    {
        if (!$this->isActive()) {
            return 0;
        }

        return UserKuota::where('user_id', $this->user_id)
            ->where('class_id', 0) // Shared quota
            ->where('invoice_number', $this->invoice_number)
            ->where('end_date', '>', now())
            ->sum('kuota');
    }

    /**
     * Check if user can book any class type in combination package
     */
    public function canBookCombinationClass()
    {
        return $this->getRemainingCombinationQuota() > 0;
    }

    /**
     * Decrease quota for combination package
     */
    public function decreaseCombinationQuota()
    {
        $quota = UserKuota::where('user_id', $this->user_id)
            ->where('class_id', 0)
            ->where('invoice_number', $this->invoice_number)
            ->where('end_date', '>', now())
            ->where('kuota', '>', 0)
            ->first();

        if ($quota) {
            $quota->decrement('kuota');
            return true;
        }

        return false;
    }

    /**
     * Scope for active memberships
     */
    public function scopeActive($query)
    {
        return $query->where('payment_status', 'paid')
            ->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'unpaid');
    }
}

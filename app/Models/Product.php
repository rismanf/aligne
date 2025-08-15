<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description', 
        'category',
        'package_type',
        'quota_strategy',
        'price',
        'kuota',
        'valid_until',
        'is_active',
        'is_popular',
        'created_by_id',
        'updated_by_id'
    ];

    // Package categories
    const CATEGORY_SIGNATURE = 'signature';
    const CATEGORY_FUNCTIONAL = 'functional';
    const CATEGORY_SPECIAL = 'special';

    // Package types for SIGNATURE CLASS PACK
    const TYPE_CORE_SERIES = 'core_series';
    const TYPE_ELEVATE_PACK = 'elevate_pack';
    const TYPE_ALIGNE_FLOW = 'aligne_flow';

    // Quota strategies
    const QUOTA_STRATEGY_FLEXIBLE = 'flexible';
    const QUOTA_STRATEGY_FIXED = 'fixed';

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'integer',
        'kuota' => 'integer',
        'valid_until' => 'integer',
    ];

    /**
     * Get available package categories
     */
    public static function getCategories()
    {
        return [
            self::CATEGORY_SIGNATURE => 'SIGNATURE CLASS PACK',
            self::CATEGORY_FUNCTIONAL => 'FUNCTIONAL CLASS PACK',
            self::CATEGORY_SPECIAL => 'SPECIAL PACKAGES',
        ];
    }

    /**
     * Get package types for signature category
     */
    public static function getSignatureTypes()
    {
        return [
            self::TYPE_CORE_SERIES => 'The Core Series',
            self::TYPE_ELEVATE_PACK => 'Elevate Pack',
            self::TYPE_ALIGNE_FLOW => 'Aligné Flow',
        ];
    }

    /**
     * Get available quota strategies
     */
    public static function getQuotaStrategies()
    {
        return [
            self::QUOTA_STRATEGY_FLEXIBLE => 'Flexible (Shared quota across class types)',
            self::QUOTA_STRATEGY_FIXED => 'Fixed (Specific quota per class type)',
        ];
    }

    /**
     * Get class memberships (quota details)
     */
    public function detailquota()
    {
        return $this->hasMany(ClassMembership::class, 'membership_id');
    }

    /**
     * Get classes included in this membership package
     */
    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_memberships', 'membership_id', 'class_id')
            ->withPivot('quota')
            ->withTimestamps();
    }

    /**
     * Get group classes included in this membership package
     */
    public function groupClasses()
    {
        return $this->belongsToMany(GroupClass::class, 'class_memberships', 'membership_id', 'class_id')
            ->withPivot('quota')
            ->withTimestamps();
    }

    /**
     * Get user memberships for this product
     */
    public function userMemberships()
    {
        return $this->hasMany(UserMembership::class, 'membership_id');
    }

    /**
     * Check if this is a signature class pack (Reformer/Chair only)
     */
    public function isSignatureClassPack()
    {
        // Check if this package only includes Reformer and Chair classes
        $classCategories = $this->groupClasses()
            ->whereIn('category', [GroupClass::CATEGORY_REFORMER, GroupClass::CATEGORY_CHAIR])
            ->pluck('category')
            ->unique();
            
        return $classCategories->count() > 0 && 
               !$this->groupClasses()->where('category', GroupClass::CATEGORY_FUNCTIONAL)->exists();
    }

    /**
     * Check if this package includes functional classes
     */
    public function includesFunctionalClasses()
    {
        return $this->groupClasses()
            ->where('category', GroupClass::CATEGORY_FUNCTIONAL)
            ->exists();
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'IDR ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get total classes count
     */
    public function getTotalClassesAttribute()
    {
        if ($this->isFlexibleQuota()) {
            // For flexible quota, return the total quota that can be used across all class types
            return $this->detailquota()->first()->quota ?? 0;
        }
        
        return $this->detailquota()->sum('quota');
    }

    /**
     * Check if this package uses flexible quota strategy
     */
    public function isFlexibleQuota()
    {
        return $this->quota_strategy === self::QUOTA_STRATEGY_FLEXIBLE;
    }

    /**
     * Check if this package uses fixed quota strategy
     */
    public function isFixedQuota()
    {
        return $this->quota_strategy === self::QUOTA_STRATEGY_FIXED;
    }

    /**
     * Get quota strategy display name
     */
    public function getQuotaStrategyNameAttribute()
    {
        $strategies = self::getQuotaStrategies();
        return $strategies[$this->quota_strategy] ?? 'Unknown';
    }

    /**
     * Get validity text
     */
    public function getValidityTextAttribute()
    {
        if (!$this->valid_until) {
            return 'No expiration';
        }
        
        return $this->valid_until . ' Days Validity';
    }

    /**
     * Scope for active packages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for signature class packs
     */
    public function scopeSignatureClassPack($query)
    {
        return $query->whereHas('groupClasses', function($q) {
            $q->whereIn('category', [GroupClass::CATEGORY_REFORMER, GroupClass::CATEGORY_CHAIR]);
        })->whereDoesntHave('groupClasses', function($q) {
            $q->where('category', GroupClass::CATEGORY_FUNCTIONAL);
        });
    }

    /**
     * Scope for functional class packs
     */
    public function scopeFunctionalClassPack($query)
    {
        return $query->whereHas('groupClasses', function($q) {
            $q->where('category', GroupClass::CATEGORY_FUNCTIONAL);
        });
    }

    /**
     * Get package category based on included classes
     */
    public function getPackageCategoryAttribute()
    {
        if ($this->isSignatureClassPack()) {
            return self::CATEGORY_SIGNATURE;
        } elseif ($this->includesFunctionalClasses()) {
            return self::CATEGORY_FUNCTIONAL;
        }
        
        return self::CATEGORY_SPECIAL;
    }

    /**
     * Get package display name based on category
     */
    public function getPackageDisplayNameAttribute()
    {
        $categories = self::getCategories();
        return $categories[$this->package_category] ?? 'SPECIAL PACKAGE';
    }
}

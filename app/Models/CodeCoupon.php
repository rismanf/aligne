<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeCoupon extends Model
{
    protected $fillable = [
        'code',
        'is_active',
        'created_by_id',
        'updated_by_id',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}

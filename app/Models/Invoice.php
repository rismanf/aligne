<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'event_id',
        'invoice_code',
        'user_id',
        'total_price',
        'total_participants',
        'status',
        'created_by_id',
        'updated_by_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
    public function participants()
    {
        return $this->hasMany(Participant::class, 'invoice_code', 'invoice_code');
    }
}

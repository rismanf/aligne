<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'event_id',
        'full_name',
        'first_name',
        'last_name',
        'phone',
        'email',
        'job_title',
        'company',
        'country',
        'type_user',
        'invoice_code',
        'coupon_code',
        'price',
        'is_active',
        'created_by_id',
        'updated_by_id'
    ];

    public function answers()
    {
        return $this->hasMany(ParticipantAnswers::class, 'participant_id');
    }
}

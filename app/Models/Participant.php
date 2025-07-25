<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'code',
        'event_id',
        'full_name',
        'first_name',
        'last_name',
        'phone',
        'email',
        'job_title',
        'company',
        'country',
        'user_type_id',
        'user_type',
        'industry',
        'invoice_code',
        'coupon_code',
        'price',
        'is_active',
        'status',
        'created_by_id',
        'updated_by_id'
    ];

    public function answers()
    {
        return $this->hasMany(ParticipantAnswers::class, 'participant_id');
    }

    public function topicAnswer()
    {
        return $this->hasOne(ParticipantAnswers::class)->where('question_id', 5);
    }

    public function golfAnswer()
    {
        return $this->hasOne(ParticipantAnswers::class)->where('question_id', 6);
    }
}

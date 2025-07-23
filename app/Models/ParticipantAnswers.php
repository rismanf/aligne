<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantAnswers extends Model
{
    protected $fillable = [
        'event_id',
        'participant_id',
        'question_id',
        'question',
        'answer_id',
        'answer',
        'created_by_id',
        'updated_by_id'
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }

    public function questions()
    {
        return $this->belongsTo(Questions::class, 'question_id');
    }
}

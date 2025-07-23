<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $fillable = [
        'event_id',
        'type_user',
        'question',
        'question_type',
        'is_active',
        'created_by_id',
        'updated_by_id',
    ];

    public function options()
    {
        return $this->hasMany(Questions_option::class, 'question_id');
    }
}

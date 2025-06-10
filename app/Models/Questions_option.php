<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questions_option extends Model
{
    protected $fillable = [
        'question_id',
        'option',
        'is_active',
        'created_by_id',
        'updated_by_id',
    ];

    public function qoutation()
    {
        return $this->belongsTo(Questions::class, 'question_id');
    }
}

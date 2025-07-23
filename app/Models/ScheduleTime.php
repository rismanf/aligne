<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleTime extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'time',
        'group_class_id',
        'group_class_name',
        'created_by_id',
        'updated_by_id',
    ];

    public function classType()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}

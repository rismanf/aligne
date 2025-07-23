<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupClass extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name',
        'description',
        'image_original',
        'created_by_id',
        'updated_by_id',
    ];
}

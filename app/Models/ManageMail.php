<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManageMail extends Model
{
    public $fillable = [
        'type',
        'name',
        'email',
    ];
}

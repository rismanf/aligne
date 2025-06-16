<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
   protected $fillable = [
        'source',
        'full_name',
        'first_name',
        'last_name',
        'company',
        'job_title',
        'country',
        'email',
        'phone',
        'message',
    ];
}

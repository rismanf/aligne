<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'slug',
        'title_slug',
        'title',
        'description',
        'keywords',
        'author',
        'image_original',
        'image_medium',
        'image_small',
        'category',
        'tags',
        'body',
        'month',
        'year',
        'is_active',
        'created_by_id',
        'updated_by_id'
    ];
}

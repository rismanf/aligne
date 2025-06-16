<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $fillable = ['name', 'slug','created_by_id', 'updated_by_id'];

    /**
     * Get the news associated with the tag.
     */
    public function news()
    {
        return $this->belongsToMany(News::class, 'news_tags', 'tag_id', 'news_id');
    }
}

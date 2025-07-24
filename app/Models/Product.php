<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function detailquota()
    {
        return $this->hasMany(ClassMembership::class, 'membership_id');
    }

   public function classes()
    {
        return $this->belongsToMany(GroupClass::class, 'class_memberships', 'membership_id', 'class_id')
            ->withPivot('quota');
    }
}

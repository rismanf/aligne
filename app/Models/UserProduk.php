<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProduk extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'product_id',
        'unique_code',
        'price',
        'total_price',
        'kuota',
        'payment_method',
        'payment_proof',
        'payment_status',
        'paid_at',
        'confirmed_by',
        'confirmed_at',
        'created_by_id',
        'updated_by_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

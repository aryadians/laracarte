<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relasi ke Order (Parent)
     * Penting untuk: whereHas('order') di laporan
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function selectedVariants()
    {
        return $this->hasMany(OrderItemVariant::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemVariant extends Model
{
    protected $guarded = [];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}

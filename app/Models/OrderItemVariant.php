<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class OrderItemVariant extends Model
{
    use HasFactory, BelongsToTenant;

    protected $guarded = [];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}

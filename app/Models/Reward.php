<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory, \App\Traits\BelongsToTenant;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'free_product_id');
    }
}

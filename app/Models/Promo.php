<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    //
    protected $fillable = ['name', 'type', 'value', 'min_purchase', 'start_date', 'end_date', 'is_active'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Added this use statement for HasFactory

class Promo extends Model
{
    use HasFactory, \App\Traits\BelongsToTenant;
    //
    protected $fillable = ['name', 'type', 'value', 'min_purchase', 'start_date', 'end_date', 'is_active'];
}

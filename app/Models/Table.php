<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Assuming HasFactory needs to be imported

class Table extends Model
{
    use HasFactory, \App\Traits\BelongsToTenant;
    protected $fillable = ['name', 'slug', 'status'];

    // Nanti kita pakai untuk generate QR Code URL
    public function getQrUrlAttribute()
    {
        return url('/order/' . $this->slug);
    }
}

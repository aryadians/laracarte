<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['name', 'slug', 'status'];
    
    // Nanti kita pakai untuk generate QR Code URL
    public function getQrUrlAttribute()
    {
        return url('/order/' . $this->slug);
    }
}

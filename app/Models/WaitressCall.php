<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitressCall extends Model
{
    use HasFactory, \App\Traits\BelongsToTenant;
    protected $guarded = [];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}

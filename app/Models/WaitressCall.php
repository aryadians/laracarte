<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaitressCall extends Model
{
    protected $guarded = [];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'logo',
    ];

    // Relationships can be defined here if needed, e.g. users, products, etc.
    public function users()
    {
        return $this->hasMany(User::class);
    }
}

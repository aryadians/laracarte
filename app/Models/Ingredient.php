<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['name', 'unit', 'stock', 'min_stock'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredients')
                    ->withPivot('quantity');
    }
}

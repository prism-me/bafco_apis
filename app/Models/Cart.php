<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'guest_id', 'product_id', 'product_variation_id', 'variation_id', 'variation_value_id', 'qty', 'unit_price', 'total'];

    public function cartProduct()
    {
        return $this->hasMany(Product::class, 'id', 'product_id', 'route');
    }

    public function productName()
    {

        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}

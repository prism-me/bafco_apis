<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'product_id','variation_id' ,'variation_value_id','qty'];

    public function cartProduct(){
        return $this->hasMany(Product::class,'id','product_id');
    }

}

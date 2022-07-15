<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','product_id','variation_id'];

   
    public function wishlistProduct(){
        return $this->belongsTo(Product::class ,'product_id','id')->select('id','name','featured_image','category_id');
    }
    
   
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variation',
        'price',
        'qty',
        'discount',
        'total'
    ];

    public function productDetail(){
            return $this->belongsTo(Product::class, 'product_id','id')->select('id','name');
    }

    public function variationDetail(){
        return $this->belongsTo(ProductVariation::class, 'product_variation','id');
    }




}

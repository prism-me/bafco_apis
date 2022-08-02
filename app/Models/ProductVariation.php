<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;
    protected $table ='product_variations';

    protected $fillable = [
        "product_id",
        "code",
        "lc_code",
        "cbm",
        "in_stock",
        "upper_price",
        "lower_price",
        "height",
        "depth",
        "width",
        "description",
        "images"
    ];


    protected $casts = [
        'images' => 'array'
    ];

    // public function getRouteKeyName()
    // {
    //     return 'route';
    // }


    public function variation_items(){

        return $this->hasMany(ProductPivotVariation::class , 'product_variation_id','id');
    }

    public function product_variation_values(){

        return $this->belongsTo(Variation::class);

    }

    public function product_variation_name(){

        return $this->hasMany(ProductPivotVariation::class);

    }


    public function product_pivot_variation(){

        return $this->hasManyThrough(ProductPivotVariation::class, ProductVariation::class , 'product_id','product_variation_id','id','id');
    }



}

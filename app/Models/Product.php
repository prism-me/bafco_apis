<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariation;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [ 
        "name",
        "short_description",
        "featured_image",
        "route",
        "long_description",
        "shiping_and_return",
        "category_id",
        "related_categories",
        "brand",
        "album",
        "download",
        "promotional_images"
    
    ];
    
    public function getRouteKeyName()
    {
        return 'route';
    }
    
    protected $casts = [
        'related_categories' => 'array',
        'album' => 'array',
        'promotional_images' => 'array'
        
    ];

    public function category(){

        return $this->hasOne(Category::class,'id','category_id');
        
    }

    public function product_variations(){

        return $this->hasMany(ProductVariation::class);
    
    }

    public function product_variation_values(){

        return $this->belongsTo(Variation::class);
        
    }
    public function product_pivot_variation(){
        
        return $this->hasManyThrough(ProductVariation::class, ProductPivotVariation::class , 'product_id','product_variation_id','id','id')->as('variations');
    }
    // public function product_variation_values(){
    //     return $this->hasMany(ProductVariation::class);
    // }

     
}



// product 
// +
// product_variation
// +
// product pivot variation (variations  ,variation values)




// product_id , product_variation_name + product_variation_value + 
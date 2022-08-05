<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPivotVariation extends Model
{
    use HasFactory;

    protected $table ='product_variation_pivot_variation_values';

    protected $fillable = [
        "product_variation_id",
        "variation_id",
        "variation_value_id",

    ];

    // public function getRouteKeyName()
    // {
    //     return 'route';
    // }


    public function variation_values(){

        return $this->belongsTo(VariationValues::class,'variation_value_id' ,'id');

    }

    public function variation_name(){

        return $this->belongsTo(Variation::class,'variation_id','id');

    }
    
    public function variation_pivot_belongs_to(){
        return $this->belongsToThrough(ProductVariation::class, Product::class );
    }

    public function variationNameValue()
    {
        return $this->belongsToThrough(
            Category::class,
            [Product::class ,ProductVariation::class],
            //'',
            //'',
            // [Product::class =>'id', ProductVariation::class => 'id' , ProductPivotVariation::class => 'variation_value_id'],
            // [Product::class =>'product_id', ProductVariation::class => 'product_variation_id' , ProductPivotVariation::class => 'id'],
        );
    }
}

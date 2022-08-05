<?php

namespace App\Models;
use \Znck\Eloquent\Traits\BelongsToThrough;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationValues extends Model
{
    use HasFactory;
    use \Znck\Eloquent\Traits\BelongsToThrough;


    protected $table = 'variation_values';
    protected $fillable = ['variation_id','name','route','type','type_value'];


    public function getRouteKeyName()
    {
        return 'route';
    }

    public function variant(){

        return $this->hasOne(Variation::class , 'id','variation_id');

    }

    // public function variationNameValue()
    // {
    //     return $this->belongsToThrough(
    //         Category::class,
    //         [Product::class ,ProductVariation::class, ProductPivotVariation::class],
    //         ['product_id', 'product_variation_id' ,'id'],
    //         [Product::class =>'id', ProductVariation::class => 'id' , ProductPivotVariation::class =>'variation_value_id'],
    //         // [Product::class =>'id', ProductVariation::class => 'id' , ProductPivotVariation::class => 'variation_value_id'],
    //     );
    // }

    public function variation_with_through()
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;
    use \Znck\Eloquent\Traits\BelongsToThrough;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;


    protected $fillable = ['name','route','type'];

    public function getRouteKeyName()
    {
        return 'route';
    }

    public function variantValues(){
        return $this->hasMany(VariationValues::class , 'variation_id' , 'id');
    }

    public function variation_with_through()
    {
        return $this->belongsToThrough(
            Category::class,
            [Product::class ,ProductVariation::class , ProductPivotVariation::class],
            '',
            '',
            [Product::class =>'product_id', ProductVariation::class => 'product_variation_id' , ProductPivotVariation::class => 'variation_id'],
            );

        // return $this->hasManyDeepFromReverse(
        //         Category::class,
        //         [Product::class ,ProductVariation::class , ProductPivotVariation::class],
        //         ['category_id', 'product_variation_id' , 'variation_id'],
        //         // ['product_id','product_variation_id' , 'id'],
        //         //'',
        //         //'',
        //         // [Product::class =>'id', ProductVariation::class => 'id' , ProductPivotVariation::class => 'variation_value_id'],
        //     );
    }

}

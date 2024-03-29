<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Product extends Model
{
    use HasFactory;
    //use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

        // protected $guarded = [];
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
       'seo',
       "promotional_images",
       "footrest",
       "headrest",
       "type",
       'top_selling'

   ];



    public function getRouteKeyName()
    {
        return 'route';
    }

    protected $casts = [
        'related_categories' => 'array',
        'album' => 'array',
        'promotional_images' => 'array',
        'seo' => 'array',
        'footrest' => 'array',
        'headrest' => 'array',

    ];

    public function category(){

        return $this->hasOne(Category::class,'id','category_id');

    }

    public function category_route(){
        return $this->hasOne(Category::class,'id','category_id')->select('id','route','parent_id');

    }

    public function cartCategory(){

        return $this->hasOne(Category::class,'id','category_id')->select('id','route','parent_id');

    }

    public function variations(){

        return $this->hasMany(ProductVariation::class);
    }


    public function product_variations(){

        return $this->hasMany(ProductVariation::class , (new ProductVariation())->product_variation_name());

    }





    public function product_pivot_table(){

        return $this->hasMany(ProductPivotVariation::class);
        // return $this->hasManyThrough(ProductPivotVariation::class ,ProductVariation::class,'id','product_variation_id','id');
    }

    public function product_variation_values(){

        return $this->belongsTo(Variation::class);

    }

    public function product_pivot_variation(){

        return $this->hasOneThrough(ProductVariation::class, ProductPivotVariation::class , 'product_id','product_variation_id','id','id');
    }




    public function cmsProductVariation(){

        return $this->hasManyThrough(ProductPivotVariation::class, ProductVariation::class , 'product_id','product_variation_id','id','id');
    }


    public function getWishlistvariations(){

        return $this->hasMany(ProductVariation::class)->select('id','product_id','in_stock');
    }




    public function front_list_of_variations(){


        return $this->hasManyDeep(
            ProductVariation::class,
            [ProductPivotVariation::class],
            ['product_variation_id' ,'id','product_id']
            ,['id','variation_id','product_variation_id']
        )->withIntermediate(ProductPivotVariation::class);



    }



    /* Product Detail Relationship */

    public function getProductDetail($fields){

        return [
                'product_variation_details' => ProductVariation::find($fields['product_variation_id']),
            'variation_details' => Variation::find($fields['variation_id']),
            'variation_value_details' => VariationValues::find($fields['variation_value_id'])
        ];

    }

    public function getProductVariation($fields){

        return [
            'variation_details' => Variation::find($fields['variation_id']),
            'variation_value_details' => VariationValues::find($fields['variation_value_id'])
        ];

    }


    public function productDetailCategory(){

        return $this->hasOne(Category::class,'id','category_id')->select('id','route','parent_id');

    }


    public function productvariations(){

        return $this->hasOne(ProductVariation::class)->select('id','product_id','code','upper_price','lower_price','in_stock','images');
    }


    public function productCategory(){

        return $this->hasOne(Category::class,'id','category_id')->select('id','parent_id','name','route');

    }
     /*   End Product Detail Relationship */

}




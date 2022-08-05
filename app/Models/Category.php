<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;


    protected $fillable = ['name','sub_title','parent_id','featured_image','banner_image','description','route','seo'];

    protected $casts = [
        'seo' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'route';
    }

    public function child(){
        return $this->hasMany(Category::class , 'parent_id','id');
    }

    public function parent(){
        return $this->hasOne(Category::class , 'parent_id','id');
    }

    public function subcategory_products(){

        return $this->hasMany(Category::class , 'parent_id','id')->with('products');
    }

    public function products(){
        return $this->hasMany(Product::class , 'category_id','id')->select('id','name','short_description','featured_image','promotional_images','route',  'category_id');;
    }


    //testing

    public function hasthroughTest(){

        return $this->hasManyThrough(ProductVariation::class , Product::class);
    
    }

    //get all variations inside
    public function deep_deep(){

    return $this->hasManyDeep(
        Variation::class,
        [ Product::class , ProductVariation::class , ProductPivotVariation::class ],
        [ 'category_id', 'product_id' , 'product_variation_id' , 'id'],
        [ 'id', 'id' , 'id','variation_id'],
    );


    }
}

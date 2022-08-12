<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


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


    public function subcategory(){

        return $this->hasMany(Category::class , 'parent_id','id');
    }
    public function parentCategory(){

        return $this->belongsTo(Category::class , 'parent_id','id' ,'route')->select('name','id','route');
    }
    public function subcategory_products(){

        return $this->hasMany(Category::class , 'parent_id','id')->with('products');
    }

    public function products(){
        return $this->hasMany(Product::class , 'category_id','id')->select('id','name','route',  'category_id');

    }







}

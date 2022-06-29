<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

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
        return $this->hasMany(Product::class , 'category_id','id');
    }

}

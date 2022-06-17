<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'route';
    }

    public function child(){
        return $this->hasMany(Category::class , 'parent_id','id')->with('child');
    }

    public function parent(){
        return $this->hasOne(Category::class , 'parent_id','id');
    }


}

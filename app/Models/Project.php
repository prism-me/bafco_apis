<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        "category_id",
        "title",
        "sub_title",
        "description",
        "featured_img",
        "additional_img",
        "related_products",
        "files",
        "route",
        "seo",
        'thumbnail_img'
    ];

    protected $casts = [
        'related_products' => 'array',
        'additional_img' => 'array',
        'category_id' => 'array',
        'files' => 'array',
        'seo' => 'array',
    ];


    public function getRouteKeyName()
    {
        return 'route';
    }


    public function projectCategory(){

        return $this->belongsToMany(ProjectCategory::class , 'project_category_pivots','project_id' , 'category_id');
    }



}

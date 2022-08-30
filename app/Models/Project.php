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
        "seo"
    ];

    protected $casts = [
        'related_products' => 'array',
        'category_id' => 'array',
        'files' => 'array',
    ];


    public function getRouteKeyName()
    {
        return 'route';
    }


}

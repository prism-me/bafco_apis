<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brochure extends Model
{
    use HasFactory;
    protected $fillable = [
        "category_id",
        "title",
        "sub_title",
        "featured_img",
        "thumbnail_img",
        "files",
        "short_description",
        "seo",
    ];

    protected $casts = [
        'category_id' => 'array',
        'files' => 'array',
        'seo' => 'array'
    ];

    public function broucherCategory(){

        return $this->belongsToMany(Category::class , 'brochures_category_pivot','brochure_id' , 'category_id');
    }
}

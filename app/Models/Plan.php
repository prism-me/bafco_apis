<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

     protected $fillable = [

       "category_id",
       "title",
       "sub_title",
       "featured_img",
       "thumbnail_img",
       "concept",
       "files",
       "route",
       "seo"

     ];

    protected $casts = [
        'featured_img' => 'array',
        'files' => 'array',
        'seo' => 'array'
    ];

    public function planCategory(){

        return $this->belongsTo(ProjectCategory::class,'category_id','id');
    }

}

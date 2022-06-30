<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'short_description', 'description' , 'tags','blog_type','posted_by','video', 'featured_img','additional_img','route'];

    public function getRouteKeyName()
    {
        return 'route';
    }
}

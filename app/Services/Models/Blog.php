<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'sub_title', 'description' , 'short_description','tags','banner_img','posted_by', 'featured_img','route','seo'];

    public function getRouteKeyName()
    {
        return 'route';
    }

    protected $casts = [
       
        'seo' => 'array',
        'tags' => 'array'
        
    ];
}

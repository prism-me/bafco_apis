<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        "type",
        "title",
        "description",
        "featured_img",
        "additional_img",
        "files",
        "route",
        "seo"
    ];
    public function getRouteKeyName()
    {
        return 'route';
    }
}

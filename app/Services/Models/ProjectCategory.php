<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCategory extends Model
{
    use HasFactory;
    protected $table = 'project_categories';


    protected $fillable = [
        "name",
        "route",
        "featured_img"
    ];
    public function getRouteKeyName()
    {
        return 'route';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['name','content','route'];
    protected $casts = [
        'content' => 'array',
    ];
    
    public function getRouteKeyName()
    {
        return 'route';
    }
}

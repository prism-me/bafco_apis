<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{

    use HasFactory;
    protected $fillable = ['name','image','route','description','link','logo'];
    
    public function getRouteKeyName()
    {
        return 'route';
    }
}

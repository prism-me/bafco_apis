<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationValues extends Model
{
    use HasFactory;
    
    protected $table = 'variation_values';
    protected $guarded = [];


    public function getRouteKeyName()
    {
        return 'route';
    }

    public function variant(){

        return $this->hasOne(Variation::class , 'id','variation_id');
    
    }
}
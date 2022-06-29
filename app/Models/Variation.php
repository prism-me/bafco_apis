<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'route';
    }

    public function variantValues(){
        return $this->hasMany(VariationValues::class , 'variation_id' , 'id');
    }


}

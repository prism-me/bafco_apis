<?php

namespace App\Models;
use \Znck\Eloquent\Traits\BelongsToThrough;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationValues extends Model
{
    use HasFactory;
    use \Znck\Eloquent\Traits\BelongsToThrough;


    protected $table = 'variation_values';
    protected $fillable = ['variation_id','name','route','type','type_value'];


    public function getRouteKeyName()
    {
        return 'route';
    }

    public function variant(){

        return $this->hasOne(Variation::class , 'id','variation_id');

    }

    public function variationNameValue()
    {
        return $this->belongsToThrough(Category::class, [Product::class ,ProductVariation::class, ProductPivotVariation::class]);
    }
    }

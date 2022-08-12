<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPivotVariation extends Model
{
    use HasFactory;

    protected $table ='product_variation_pivot_variation_values';

    protected $fillable = [
        "product_id",
        "product_variation_id",
        "variation_id",
        "variation_value_id",

    ];




    public function variation_values(){

        return $this->belongsTo(VariationValues::class,'variation_value_id' ,'id');

    }
    public function variation_name(){

        return $this->belongsTo(Variation::class,'variation_id','id');

    }

    public function productVariationValues(){

        return $this->belongsTo(VariationValues::class,'variation_value_id' ,'id')->select('id','variation_id','name');

    }
}

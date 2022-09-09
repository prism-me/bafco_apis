<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $fillable = ['name'];


    public function materialValues(){

          return $this->belongsToMany(FinishesValue::class ,'material_finishes_pivot','material_id','finishes_value_id');
    }


}

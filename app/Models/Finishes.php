<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finishes extends Model
{
    use HasFactory;
    protected $fillable = ['name','parent_id','seo'];
    protected $casts = [
        'seo' => 'array'
    ];

    public function child(){
        return $this->hasMany(Finishes::class , 'parent_id','id')->with('child');
    }

    public function parent(){
        return $this->hasOne(Finishes::class,'id' , 'parent_id');
    }

    public function childValue(){
        return $this->hasMany(Finishes::class , 'parent_id','id')->with('child.value');
    }


    public function value(){
    
        return $this->hasOne(FinishesValue::class , 'finishes_id','id');
    
    }


}
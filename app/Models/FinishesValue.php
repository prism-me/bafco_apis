<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishesValue extends Model
{
    use HasFactory;
    protected $fillable = ['finishes_id','title','featured_img','code','additional_img','material_id','color_code'];
    protected $casts = [
        'seo' => 'array',
        'additional_img'=>'array'
    ];


    public function values(){
        return $this->belongsTo(Finishes::class  ,'finishes_id','id' );
    }
}

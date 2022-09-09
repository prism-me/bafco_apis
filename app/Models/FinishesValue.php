<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishesValue extends Model
{
    use HasFactory;
    protected $fillable = ['finishes_id','featured_img','code'];

    public function values(){
        return $this->belongsTo(Finishes::class  ,'finishes_id','id' );
    }
}

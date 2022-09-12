<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishesValuePivot extends Model
{
    protected $table = 'material_finishes_pivot';
    use HasFactory;
    protected $fillable = ['material_id','finishes_id','finishes_value_id'];

}

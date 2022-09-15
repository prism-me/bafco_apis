<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrochureCategoryPivot extends Model
{
    use HasFactory;
    protected $table = "brochures_category_pivot";
    protected $fillable = [
        "brochure_id",
        "category_id"
    ];
}

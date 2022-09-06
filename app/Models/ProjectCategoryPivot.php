<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCategoryPivot extends Model
{
    use HasFactory;
    protected $fillable = [
        "project_id",
        "category_id"
    ];
}

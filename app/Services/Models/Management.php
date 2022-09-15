<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Management extends Model
{
    use HasFactory;
    protected $table ='managements';

    protected $fillable = ['type','content'];
    protected $casts = [
        'content' => 'array',
    ];
    
    public function getRouteKeyName()
    {
        return 'type';
    }
}

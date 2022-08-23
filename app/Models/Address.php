<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = ['user_id',
        'country',
        'state',
        'city',
        'address_line1',
        'address_line2',
        'postal_code',
        'phone_number',
        'default',
        'address_type'
    ];

}

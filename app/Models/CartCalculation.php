<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartCalculation extends Model
{

    use HasFactory;
    protected $table = 'cart_calculation';

    protected $fillable = ['user_id', 'coupon','discounted_price' ,'total','sub_total'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartCalculation extends Model
{

    use HasFactory;
    protected $table = 'cart_calculation';

    protected $fillable = ['user_id', 'guest_id', 'coupon', 'discounted_price', 'shipping_charges', 'decimal_amount', 'total', 'sub_total'];
}

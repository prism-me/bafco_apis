<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestCartCalculation extends Model
{
    use HasFactory;
    protected $table = 'guest_cart_calculations';

    protected $fillable = ['user_id', 'coupon','discounted_price' ,'shipping_charges','decimal_amount','total','sub_total'];
}

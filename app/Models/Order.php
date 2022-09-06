<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id'	,
        'order_number',
        'payment_id',
        'transaction_status',
        'paid',
        'payment_date',	
    ];


    public function order_details(){
        return $this->hasMany(OrderDetail::class , 'order_id' , 'id');
    }
}

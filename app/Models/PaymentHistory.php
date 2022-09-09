<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'payment_history';

    protected $fillable = ['user_id', 'user_email', 'order_id',  'reference_number', 'captured', 'payment_date', 'amount'];
}

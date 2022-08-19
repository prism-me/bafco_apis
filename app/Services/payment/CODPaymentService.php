<?php 

namespace App\Services\payment;

use App\Interfaces\PaymentInterface;

class CODPaymentService implements PaymentInterface { 

    public function makePayment($request){
        return 'make payment';
    }

    public function defferredPayment($request){
        return "deffered payment";
    }

    

}
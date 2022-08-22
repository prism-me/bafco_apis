<?php

namespace App\Services\payment;

use App\Interfaces\PaymentInterface;

class PaymentService {
      



      public function pay(PaymentInterface $paymentInterface , $request){
            return $paymentInterface->makePayment($request);
      }

      public function capture(PaymentInterface $paymentInterface , $request){
            return $paymentInterface->capturePayment($request);
      }


}
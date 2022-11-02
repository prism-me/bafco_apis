<?php

namespace App\Services\payment;

use App\Interfaces\PaymentInterface;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Services\OrderService;

class PaymentService
{



      public function guestCheckoutService($request)
      {
            return 'not implemented';
      }

      public function authCheckoutService(PaymentInterface $paymentInterface, $request)
      {

            $data = $paymentInterface->makePayment($request);
            return $data;
      }

      public function capturePaymentDetails(PaymentInterface $paymentInterface, $request)
      {

            if (!empty($request->order_id) && $request->status === 'APPROVED') {
                  $data = $paymentInterface->capturePayment($request->order_id);
                  return $data;
            }
      }


      //direct payment test
      public function pay(PaymentInterface $paymentInterface, $request)
      {
            dd('died');
            return $paymentInterface->makePayment($request);
      }

      //direct capture payment test
      public function capture(PaymentInterface $paymentInterface, $request)
      {
            dd('died');
            return $paymentInterface->capturePayment($request);
      }
}

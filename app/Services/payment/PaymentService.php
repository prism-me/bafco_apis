<?php

namespace App\Services\payment;

use App\Interfaces\PaymentInterface;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class PaymentService {

      protected $promoValidity = 1;

      public $order_number;

      public $success_url = 'http://127.0.0.1:8080/api/paymentSuccess';

      public $failed_url = 'http://127.0.0.1:8080/api/paymentFailed';

      public function pay(PaymentInterface $paymentInterface , $request){
           
            return $paymentInterface->makePayment($request);
      
      }

      public function capture(PaymentInterface $paymentInterface , $request){
            
            return $paymentInterface->capturePayment($request);

      }

      public function guestCheckoutService($request){
            return 'not implemented';

      }
      
      public function authCheckoutService($request){
            
            $cartList = Cart::select(['id','product_id','qty','total'])->where('user_id',$request->user_id)->get();

            foreach($cartList as $cart){
                 $cart->product_name= Product::where('route', $cart->route)->get();
            }
            // $promoCode = $this->promoCodeCheck($request->coupon_code);

            // if(!$promoCode) return response()->json('promo code is expired.' , 400);

            $this->order_number = 'OR'. rand(999 , 888888999999);
            
            $mapedObject = $this->mapPaymentObject($request->all() , $cartList);
            return $mapedObject;
      }

      public function promoCodeCheck($coupon_code){

            $ifValid = DB::table('promo_codes')
                  ->where('name', $coupon_code)
                  ->whereDate('end_date', '>=', date("Y-m-d"))
                  ->get();
                  
                  return ($ifValid) ? $ifValid : false;
      }

      public function mapPaymentObject($data , $cartList){
            $customer = auth()->user();
             $data['customer']['name'] = $customer->name;
             $data['customer']['email'] = $customer->email;
             $data['item'] = $cartList;
             return $data;

      }


}

$object = [
      // "order_id" => $this->order_number,
      // "total_amount" => 840,
      // "tax_amount" => 3880,
      // "currency" => "AED",
      // "shipping" => [{
      //   "id"  : "shipping-01",
      //   "name"  : "Express Delivery",
      //   "amount"  : 2000,
      //   "address"  : {
      //     "first_name"  : "John",
      //     "last_name"  : "Doe",
      //     "phone"  : "+971 50 199 8853",
      //     "alt_phone"  : "800 239",
      //     "line1"  : "The Gate District, DIFC",
      //     "line2"  : "Level 4, Precinct Building 5",
      //     "city"  : "Dubai",
      //     "state"  : "Dubai",
      //     "country"  : "AE",
      //     "postal_code"  : "00000"
      //   }
      // }],
      // "billing_address"  => {
      //   "first_name"  => "John",
      //   "last_name"  => "Doe",
      //   "phone"  => "+971 50 199 8853",
      //   "alt_phone"  => "800 239",
      //   "line1"  => "The Gate District, DIFC",
      //   "line2"  => "Level 4, Precinct Building 5",
      //   "city"  => "Dubai",
      //   "state"  => "Dubai",
      //   "country"  => "AE",
      //   "postal_code"  => "00000"
      // },
      // "customer"  => {
      //   "id"  => "customer-01",
      //   "email"  => "john@postpay.io",
      //   "first_name"  => "John",
      //   "last_name"  => "Doe",
      //   "gender"  => "male",
      //   "account"  => "guest",
      //   "date_of_birth"  => "1990-01-20",
      //   "date_joined"  => "2019-08-26T09:28:14.790Z"
      // },
      // "items" => $cartList,
      // "discounts"  => [
      //   {
      //     "code"  => "return-10",
      //     "name"  => "Returning customer 10% discount",
      //     "amount"  => 8400
      //   }
      // ],
      // "merchant"  => {
      //   "confirmation_url" : "http://bafco-next.herokuapp.com/",
      //   "cancel_url"  : "http://bafco-next.herokuapp.com/"
      // }
];

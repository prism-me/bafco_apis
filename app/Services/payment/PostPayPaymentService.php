<?php

namespace App\Services\payment;

use App\Interfaces\PaymentInterface;
use App\Jobs\OrderPlacedJob;
use App\Mail\OrderPlaced;
use App\Models\Cart;
use App\Models\PaymentHistory;
use App\Models\Product;
use App\Models\PromoUser;
use App\Models\User;
use App\Models\Order;
use App\Models\CartCalculation;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Postpay\Exceptions\RESTfulException;

class PostPayPaymentService implements PaymentInterface
{
    const STATUS = 'abc';

    public $baseURL;
    public $baseCode;
    public $promoValidity = 1;
    public $order_number;
    //public $success_url = 'http://localhost:8000/v1/api/paymentSuccess';
    public $success_url = 'https://prismcloudhosting.com/BAFCO_APIs/public/v1/api/paymentSuccess';
    //public $failed_url = 'http://localhost:8000/v1/api/paymentFailed';
     public $failed_url = 'https://prismcloudhosting.com/BAFCO_APIs/public/v1/api/paymentFailed';

    public function __construct()
    {

        $this->baseURL = config('postpay.base_url');
        $this->baseCode = config('postpay.base_code');
    }

    public function makePayment($request)
    {
        try {
            DB::beginTransaction();
            if (isset($request->guest_id)) {

                $user = (new UserService())->createUser($request);

                if (!empty($user)) {
                    $user_id = $user['user_id'];
                    $request['user_id'] = $user_id;

                    $request->address_id = $user['address_id'];

                    $cart = (new CartService())->guestCartToUserCart($request->guest_id, $user_id);
                    if (!$cart) throw new  \Exception("User does not have any item in cart", 1);
                }
            } else {
                $address = (new UserService())->createAddress($request);
                $user_id = $request->user_id;
                $request->address_id = $address->id;
            }
            
           
           
            $cartList = Cart::where('user_id', $user_id)->get(['product_id', 'product_variation_id', 'qty', 'total', 'unit_price', 'user_id']);
       
            foreach ($cartList as $cart) {

                $cart['name'] =  Product::where('id', $cart->product_id)->first()['name'];
                $cart['reference'] = $cart->product_id;
                $cart['unit_price'] = $cart->unit_price;
            }
           

            if (isset($request->discounts[0]['name'])) {

                $promoCode = $this->promoCodeCheck($request->discounts[0]['name']);
                // if (!$promoCode) return response()->json('promo code is expired.', 400);
            } else {
                $promoCode = 'TEST';
            }

            $this->order_number = 'OR' . rand(999, 888888999999);
            $mapedObject = $this->mapPaymentObject($request->all(), $cartList, $promoCode , $user_id);
            
            $order = (new OrderService())->createOrder($mapedObject, $user_id, $request);
            if (!$order) throw new  \Exception("Error while processing order", 1);
            $payment = $this->createPayment($request, $user_id);

            if (!$payment) throw new  \Exception("Error while processing order", 1);

            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->baseCode,
            ])->post($this->baseURL . '/checkouts', $mapedObject);

            $data = $response->json();

            DB::commit();

           return ($response->getStatusCode() == 200 && !empty($data->token)) ? $data->redirect_url :  $data;
        } catch (RESTfulException $e) {

            DB::rollBack();
            return response()->json(['ex_message' => $e->getMessage(), 'error' => $e->getErrorCode(), 'line' => $e->getLine()]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return $e;
            return response()->json(['ex_message' => $e->getMessage(), 'error' => $e , 'line' => $e->getLine()]);
        }
    }

    public function promoCodeCheck($coupon_code)
    {

        $ifValid = DB::table('promo_codes')
            ->where('name', $coupon_code)
            ->whereDate('end_date', '>=', date("Y-m-d"))
            ->first(['name']);

        return ($ifValid) ? $ifValid : false;
    }

    public function mapPaymentObject($data, $cartList, $promoCode,$user_id)
    {
        

        $cartAmount = CartCalculation::where('user_id',$user_id)->first();
        
        if( $data['discounts'][0]['code'] == "BAFCOTest"){
         
            $data['order_id'] = $this->order_number;
            $data['items'] = $cartList;
            $data['merchant']['cancel_url'] = $this->failed_url;
            $data['merchant']['confirmation_url'] = $this->success_url;
            $data['promocode'] = $promoCode ? $promoCode : null;
            $data['num_instalments'] = $data['num_instalments'];
            $data['total_amount'] = $data['total_amount'];
            return $data;
            
        }else{
            
            $updateAmount['coupon'] =  $data['discounts'][0]['name'];
            $updateAmount['discounted_price'] = $data['discounts'][0]['amount'];
            $updateAmount['total'] = $cartAmount['total'] - $data['discounts'][0]['amount'];
            $updateAmount['decimal_amount'] =  $updateAmount['total'] * 100;
            CartCalculation::where('user_id', $data['user_id'])->update($updateAmount);
            PromoUser::create([
               'user_id' => $data['user_id'],
                "promo_code_id" => $data['discounts'][0]['name']
            ]);
           
            $updatedAmount = CartCalculation::where('user_id',$data['user_id'])->first();
            $data['order_id'] = $this->order_number;
            $data['items'] = $cartList;
            $data['merchant']['cancel_url'] = $this->failed_url;
            $data['merchant']['confirmation_url'] = $this->success_url;
            $data['promocode'] = $promoCode ? $promoCode : null;
            $data['num_instalments'] = $data['num_instalments'];
            $data['total_amount'] = $updatedAmount['decimal_amount'];
            return $data;
            
        }
       
    }

    public function createPayment($data, $user_id)
    {

        $order = Order::where('order_number',$this->order_number)->pluck('total');
        $payment = PaymentHistory::create([
            'user_id' =>  $user_id,
            'user_email' => $data['customer']['email'],
            'order_id' => $this->order_number,
            'reference_number' => Null,
            'captured' => false,
            'amount' => $order,
            'status' => 'pending',
            'num_instalments' => $data['total_amount'] !== 3 ? 0 : 1 
        ]);
        return $payment ? true : false;
    }



    public function capturePayment($order_id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->baseCode,
            ])->post($this->baseURL . '/orders' . '/' . $order_id . '/capture');

            $data = $response->json();
            if ($response->getStatusCode() == 200 && $data['order_id'] == $order_id && $data['status'] == 'captured') {
                $status = 'captured';
            } else if ($response->getStatusCode() == 402 && $data['error'] == 'capture_error') {
                $status = 'capture_error';
            } else if ($response->getStatusCode() == 409 && $data['error'] == 'invalid_status') {
                $status = 'invalid_status';
            } else if ($response->getStatusCode() == 410 && $data['error'] == 'expired') {
                //expired capture
                $status = 'expired';
            } else {
                //general error
                $status = 'not_found';
            }

            $order = (new OrderService())->updateOrderAfterPayment($order_id, $data['reference'], $status);

            return ['order' => $order, 'status' => $status, 'order_id' => $order_id, 'status' => $response->getStatusCode()];
            // return ($order)
            //     ? redirect()->away('https://bafco-next.herokuapp.com/checkout?status=success')
            //     : response()->json(['message' => 'Internal Error while payment.', 'status' => $status], 404);
        } catch (RESTfulException $e) {
            return response()->json(['ex_message' => $e->getMessage(), 'error' => $e->getErrorCode(), 'line' => $e->getLine()]);
        }
    }
}

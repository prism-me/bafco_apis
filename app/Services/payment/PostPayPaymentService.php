<?php

namespace App\Services\payment;

use App\Interfaces\PaymentInterface;
use App\Models\Cart;
use App\Models\PaymentHistory;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Postpay\Exceptions\RESTfulException;

class PostPayPaymentService implements PaymentInterface
{
    const STATUS = 'abc';

    public $baseURL;
    public $baseCode;
    public $promoValidity = 1;
    public $order_number;
    public $success_url = 'http://localhost:8000/v1/api/paymentSuccess';
    // public $success_url = 'https://prismcloudhosting.com/BAFCO_APIs/public/v1/api/paymentSuccess';
    public $failed_url = 'http://localhost:8000/v1/api/paymentFailed';
    // public $failed_url = 'https://prismcloudhosting.com/BAFCO_APIs/public/v1/api/paymentFailed';

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
                    $user_id = $user;
                    $cart = (new CartService())->guestCartToUserCart($request->guest_id, $user);
                    if (!$cart) {
                        return response()->json('User does not have any item in cart.', 200);
                    }
                }
            } else {
                $user_id = $request->user_id;
            }
            // exit;
            $cartList = Cart::where('user_id', $user_id)->get(['product_id', 'product_variation_id', 'qty', 'total', 'unit_price']);

            foreach ($cartList as $cart) {

                $cart['name'] =  Product::where('id', $cart->product_id)->first()['name'];
                $cart['reference'] = $cart->product_id;
                $cart['unit_price'] = $cart->unit_price;
            }

            if (isset($request->coupon_code)) {

                $promoCode = $this->promoCodeCheck($request->coupon_code);

                if (!$promoCode) return response()->json('promo code is expired.', 400);
            }

            $this->order_number = 'OR' . rand(999, 888888999999);

            $mapedObject = $this->mapPaymentObject($request->all(), $cartList, $promoCode);

            $order = (new OrderService())->createOrder($mapedObject, $user_id);

            if (!$order) throw new  \Exception("Error Processing Request", 1);
            
            $payment = $this->createPayment($request, $user_id);

            if (!$payment) throw new  \Exception("Error Processing Request", 1);
            
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
            return response()->json(['ex_message' => $e->getMessage(), 'line' => $e->getLine()]);
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

    public function mapPaymentObject($data, $cartList, $promoCode)
    {

        $data['order_id'] = $this->order_number;
        $data['items'] = $cartList;
        $data['merchant']['cancel_url'] = $this->failed_url;
        $data['merchant']['confirmation_url'] = $this->success_url;
        $data['promocode'] = $promoCode;
        return $data;
    }

    public function createPayment($data, $user_id)
    {
        //rest payment status will be updated in updateOrderAfterPayment function of OrderService Class
        $payment = PaymentHistory::create([
            'user_id' =>  $user_id,
            'user_email' => $data['customer']['email'],
            'order_id' => $this->order_number,
            'reference_number' => Null,
            'captured' => false,
            'amount' => $data['total_amount'],
            'status' => 'pending'
        ]);
        return $payment ? true : false;
    }






    public function capturePayment($order_id)
    {
        try {
            // $response = Http::withHeaders([
            //     'Authorization' => 'Basic ' . $this->baseCode,
            // ])->post($this->baseURL . '/orders' . '/' . $order_id . '/capture');

            // $data = $response->json();

            // if ($response->getStatusCode() == 200 && $data['order_id'] == $order_id && $data['status'] == 'captured') {

            $order = (new OrderService())->updateOrderAfterPayment($order_id, 'reference');
            return $order;
            return ($order) ?
                response()->json('Order has been Placed.') :
                response()->json('Internal Error while payment.');
            // } else if ($response->getStatusCode() == 402 && $data['error'] == 'capture_error') {
            //     //payment transaction got failed
            //     return response()->json($data['message'], 402);
            // } else if ($response->getStatusCode() == 409 && $data['error'] == 'invalid_status') {
            //     //invalid status
            //     return response()->json($data['message'], 409);
            // } else if ($response->getStatusCode() == 410 && $data['error'] == 'expired') {
            //     //expired capture
            //     return response()->json($data['message'], 410);
            // } else {
            //     //general error
            //     return response()->json('Network Error', 400);
            // }
        } catch (RESTfulException $e) {
            return response()->json(['ex_message' => $e->getMessage(), 'error' => $e->getErrorCode(), 'line' => $e->getLine()]);
        }
    }
}

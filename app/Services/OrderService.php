<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartCalculation;
use App\Models\GuestCart;
use App\Models\GuestCartCalculation;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\DB;


class OrderService
{

    public function createOrder($orderData, $user_id , $request)
    {
        try {
            // dd($request->address_id);
            $cartDeatils = CartCalculation::where('user_id', $user_id)->first();
            // dd($cartDeatils);
            DB::beginTransaction();
            $order = Order::create([
                'user_id' => $user_id,
                'order_number' => $orderData['order_id'],
                'payment_id' => 1,
                'transaction_status' => 0,
                'paid' => 0,
                'address_id' => $request->address_id,
                'coupon' => $orderData['promocode'],
                'discount' => $cartDeatils['discount'],
                'shipping_charges' => $cartDeatils['shipping_charges'],
                'total' => $cartDeatils['total'],
                'sub_total' => $cartDeatils['sub_total'],
                'status' => 'ORDERPLACED',
                'payment_date' => null,
            ]);
            foreach ($orderData['items'] as $item) {
                $order->order_details()->create([
                    'product_id' => $item['product_id'],
                    'product_variation' => $item['product_variation_id'],
                    'price' => $item['unit_price'],
                    'qty' => $item['qty'],
                    'discount' => 'discount',
                    'total' => $item['total']

                ]);
            }
            // dd( DB::getQueryLog());
            DB::commit();
            return true;
        } catch (\Exception $e) {

            DB::rollBack();
            return false;
        }
    }

    public function updateOrderAfterPayment($orderData, $reference, $status)
    {

        try {
            DB::beginTransaction();
            $order = Order::where('order_number', $orderData)->first();
            $order->transaction_status = 1;
            $order->paid = 1;
            $order->payment_date = \Carbon\Carbon::now()->toDateTimeString();
            $order->save();


            $payment = PaymentHistory::where('order_id', $orderData)->first();;
            $payment->reference_number =  $reference;
            $payment->captured =  true;
            $payment->payment_date =  \Carbon\Carbon::now();
            $payment->status = $status;
            $payment->save();

            $cart = Cart::where('user_id', $order->user_id)->firstOrFail();
            if(isset($cart->guest_id)){
                GuestCart::where('guest_id', $cart->guest_id)->delete();
            }
            $cart->delete();

            $cartCal = CartCalculation::where('user_id', $order->user_id)->firstOrFail();
            if(isset($cartCal->guest_id)){
                GuestCartCalculation::where('guest_id', $cart->guest_id)->delete();
            }
            $cartCal->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {

            // DB::rollBack();
            return false;
        }
    }
}

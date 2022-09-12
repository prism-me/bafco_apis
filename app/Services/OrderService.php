<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartCalculation;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\DB;

class OrderService
{


    public function createOrder($orderData)
    {
        try {


            $cartDeatils = CartCalculation::where('user_id', $orderData['user_id'])->first();
            DB::beginTransaction();
            $order = Order::create([
                'user_id' => $orderData['user_id'],
                'order_number' => $orderData['order_id'],
                'payment_id' => 1,
                'transaction_status' => 0,
                'paid' => 0,
                'coupon' => $orderData['promocode']->name,
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
                    'total' => $item['unit_price'] * $item['qty']

                ]);
            }

            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();
            return false;
        }
    }

    public function updateOrderAfterPayment($orderData, $reference)
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
            $payment->save();

            $cart = Cart::where('user_id', $order->user_id)->firstOrFail();
            $cart->delete();

            $cartCal = CartCalculation::where('user_id', $order->user_id)->firstOrFail();
            $cartCal->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {

            DB::rollBack();
            return false;
        }
    }
}

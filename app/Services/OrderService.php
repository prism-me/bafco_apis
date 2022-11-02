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

            $cart = Cart::where('user_id', $order->user_id)->pluck('id');
            $guestIds = Cart::where('user_id', $order->user_id)->pluck('guest_id');

            if (!empty($guestIds)) {
                $guestCartIds = GuestCart::whereIn('user_id', $guestIds)->pluck('id');
                GuestCart::destroy($guestCartIds);
            }
            Cart::destroy($cart);


            $cartCal = CartCalculation::where('user_id', $order->user_id)->firstOrFail();
            if (!empty($cartCal->guest_id)) {
                $guestCartCalculation = GuestCartCalculation::where('user_id', $cartCal->guest_id)->firstOrFail();
                $guestCartCalculation->delete();
            }
            $cartCal->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {

            // DB::rollBack();
            return false;
        }
    }


    public function payment_failed($orderData, $status) {

        //we have to delete the order

        try {
            DB::beginTransaction();
            $order = Order::where('order_number', $orderData)->first();
            $order->transaction_status = 0;
            $order->status = $status;
            $order->paid = 0;
            $order->save();


            $payment = PaymentHistory::where('order_id', $orderData)->first();;
            $payment->captured =  false;
            $payment->status =  $status;
            $payment->save();

            DB::commit();

            return true;
            
        } catch (\Exception $e) {

            DB::rollBack();
            return false;
        }
    }
}

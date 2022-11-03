<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartCalculation;
use App\Models\GuestCart;
use App\Models\GuestCartCalculation;
use App\Models\Order;
use App\Models\PromoUser;
use App\Models\OrderDetail;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\DB;


class OrderService
{

    public function createOrder($orderData, $user_id , $request)
    {
        
       
       
        try {
            $cartDetails = CartCalculation::where('user_id',$user_id)->first();
           
            DB::beginTransaction();
            $order = Order::create([
                'user_id' => $user_id,
                'order_number' => $orderData['order_id'],
                'payment_id' => 1,
                'transaction_status' => 0,
                'paid' => 0,
                'address_id' => $request->address_id,
                'coupon' => $orderData['discounts'][0]['name'],
                'discount' => $orderData['discounts'][0]['code'],
                'shipping_charges' => $cartDetails['shipping_charges'],
                'total' => $cartDetails['total'],
                'sub_total' => $cartDetails['sub_total'],
                'status' => 'ORDERPLACED',
                'payment_date' => null,
                'num_instalments' => $orderData['total_amount'] !== 3 ? 0 : 1 
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

            DB::rollBack();
            return false;
        }
    }


    public function payment_failed($orderData, $status) 
    {
        
        
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
    
    
                $cartCalculation = CartCalculation::where('user_id',$order['user_id'])->first();
                $sub_total = $cartCalculation['discounted_price'] + $cartCalculation['total'];
                $decimal_amount = $sub_total * 100;
                $cartCalculation->coupon = NULL;
                $cartCalculation->discounted_price = NULL;
                $cartCalculation->sub_total = $sub_total;
                $cartCalculation->decimal_amount = $decimal_amount;
                $cartCalculation->save();
            
            
            if($order['coupon'] == "BAFCOTest"){
                
                $BAFCOTest = PromoUser::where('user_id', $order['user_id'])->where('promo_code_id','BAFCOTest')->delete();

            }else{
                
                
                $BAFCOTest = PromoUser::where('user_id', $order['user_id'])->where('promo_code_id','BAFCOTest')->delete();
                $promoUser = PromoUser::where('user_id', $order['user_id'])->first();
                $promoUser->destroy();

                
            }

           
            DB::commit();

            return true;
            
        } catch (\Exception $e) {

            DB::rollBack();
            return false;
        }
    }
}

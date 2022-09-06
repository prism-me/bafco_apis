<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;

class OrderService {


    public function createOrder($orderData) {
       
        $order = Order::create(
            [
                'user_id' => $orderData['user_id'],
                'order_number' => $orderData['order_id'],
                'payment_id' =>1,
                'transaction_status' => 0,
                'paid' => 0,
                'payment_date' =>null,
            ]
        );

        foreach($orderData['items'] as $item) {

            $order->order_details()->create([
                'product_id' => $item['product_id'],
                'product_variation'=> $item['product_variation_id'],
                'price'=> $item['unit_price'],
                'qty'=> $item['qty'],
                'discount'=>'discount',
                'total' => $item['unit_price'] * $item['qty']
            
            ]);
        }
    }

    public function updateOrderAfterPayment($orderData){

        $order = Order::where('order_id', $orderData)->first();
        $order->transaction_status = 1;
        $order->paid = 1;
        $order->payment_date = \Carbon\Carbon::now()->toDateTimeString();
        if($order->save()){

            Cart::where('user_id' , $order->user_id)->delete();
            cartDetail::where('user_id' , $order->user_id)->delete();

        }

    }

}
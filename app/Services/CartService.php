<?php

namespace App\Services;
use App\Models\Cart;


class CartService {

    public function addToCart($data){

        $create = [
            'user_id' =>  $data['user_id'],
            'product_id' => $data['product_id'] ,
            'qty' =>  $data['qty'] ,
            'variation_id' =>  $data['variation_id'] ,
            'variation_value_id' =>  $data['variation_value_id']

        ];
        $cartValue = Cart::where('product_id', $data['product_id'])->where('variation_id', $data['variation_id'])->where('user_id',$data['user_id'])->first();


        if( $cartValue){

            $create['qty'] = $cartValue['qty']  + 1;
             #update
            $cart = $cartValue->update($create);

        }else{

            #create
            $cart = Cart::create($create);
        }

        if($cart){

            return  response()->json('Data has been saved.' , 200);
        }

    }
}

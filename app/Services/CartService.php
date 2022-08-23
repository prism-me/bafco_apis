<?php

namespace App\Services;
use App\Models\Cart;
use App\Models\CartCalculation;
use App\Models\Product;
use DB;



class CartService {

    public function addToCart($data){

        $create = [
            'user_id' =>  $data['user_id'],
            'product_id' => $data['product_id'] ,
            'product_variation_id' => $data['product_variation_id'] ,
            'qty' =>  $data['qty'] ,
            'variation_id' =>  $data['variation_id'] ,
            'variation_value_id' =>  $data['variation_value_id']

        ];
        $cartValue = Cart::where('product_id', $data['product_id'])->where('variation_id', $data['variation_id'])->where('user_id',$data['user_id'])->first();

        if( $cartValue){

            $create['qty'] = $cartValue['qty'] + 1;

            $cartUpdate = $cartValue->update($create);
            return  $cartValue->refresh();


        }else{

            #create
            $cart = Cart::create($create);

            return $cart;


        }



    }
}

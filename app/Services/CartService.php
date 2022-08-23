<?php

namespace App\Services;
use App\Models\Cart;
use App\Models\ProductVariation;

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
        ];
        $cartValue = Cart::where('product_id', $data['product_id'])->where('product_variation_id', $data['product_variation_id'])->where('user_id',$data['user_id'])->first();

        if( $cartValue){

            $create['qty'] = $cartValue['qty'] + 1;

            $cartUpdate = $cartValue->update($create);
            $cart = Cart::where('id',$cartValue->id)->first();
            $cartData = (new CartService)->cartDetail($cart);
            return $cartData;

        }else{

            #create
            $cart = Cart::create($create);
            $cart = Cart::where('id',$cart->id)->first();
            $cartData = (new CartService)->cartDetail($cart);
            return $cartData;

        }




    }

    public function test(){
        return 'test';
    }

    public function incrementQty($data){

        $data['qty'] = $data['qty'];
        $cartUpdate = Cart::where('id',$data['cart_id'])->update($data);
        $cart = Cart::where('id',$data['cart_id'])->first();
        $cartData = $this->cartData($cart);
        return $cartData;

        if($cartData){

            return response()->json($cartData , 200);

        }else{

            return response()->json('Something went wrong!', 404);
        }

    }

    public function cartDetail($cart){

        try {


            //DB::beginTransaction();
                $productDetail = Product::where('id',$cart->product_id)->with('cartCategory.parentCategory' )->first(['name','featured_image','route','category_id']);
                $productVariant = ProductVariation::where('id',$cart->product_variation_id)->with('productVariationName.productVariationValues.variant')->first(['id','code','upper_price']);
                $quantity =  $cart->qty;
                $price = $productVariant['upper_price'];
                $finalPrice = $quantity * $price;
                $cartPrice = Cart::where('id',$cart->id)->update(['total' => $finalPrice]);

                $cartCalculation =   [
                    'user_id' => $cart->user_id ,
                    'total' => $finalPrice,
                    'sub_total' => $finalPrice
                ];



                if($cartcal = CartCalculation::where('user_id',$cart->user_id)->exists()){

                    CartCalculation::where('user_id',$cart->user_id)->update($cartCalculation);

                }else{

                    $cartCalculation = CartCalculation::create($cartCalculation);

                }

            //DB::commit();
                $data = $productDetail;
                $data['variation'] = $productVariant;
                $data['quantity'] = $quantity;
                $data['total'] = $finalPrice;

                return $data;

        } catch (\Exception $e) {

            //DB::rollBack();
                return response()->json(['Product is not added.', 'stack' => $e], 500);
        }



    }
}

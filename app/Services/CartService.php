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

  

    public function incrementQty($data){

        
        $cart = Cart::where('id',$data['cart_id'])->first();
        $update['qty'] = $data['qty'] + $cart->qty;
        $cartUpdate = Cart::where('id',$data['cart_id'])->update($update);
        $cartData = (new CartService)->cartDetail($cart); 
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
                $productVariant = ProductVariation::where('id',$cart->product_variation_id)->with('productVariationName.productVariationValues.variant')->first(['id','code','lower_price','upper_price','limit']);
                $quantity =  $cart->qty;
                $limit = $productVariant['limit'];
                
                if($quantity > $limit){

                    $price = $productVariant['lower_price'];
                    $updateCart['unit_price'] = $productVariant['lower_price'];

                }else{

                    $price = $productVariant['upper_price'];
                    $updateCart['unit_price'] = $productVariant['upper_price'];
                }

              
                
                $updateCart['total'] = $quantity * $price;
                $cartUpdated = Cart::where('id',$cart->id)->update($updateCart);
                
                $cartPrice = Cart::where('id',$cart->id)->first();
                $cartCalculation =   [
                    'user_id' => $cart->user_id ,
                    'total' => $cartPrice['total'],
                    'sub_total' =>  $cartPrice['total']
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
                $data['total'] = $cartPrice['total'];

                return $data;

        } catch (\Exception $e) {

            //DB::rollBack();
                return response()->json(['Cart not updated.', 'stack' => $e], 500);
        }



    }
}

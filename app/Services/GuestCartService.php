<?php

namespace App\Services;
use App\Models\GuestCart;
use App\Models\ProductVariation;
use App\Models\GuestCartCalculation;
use App\Models\Product;
use DB;



class GuestCartService {

    public function addToCart($data){

        $create = [
            'user_id' =>  $data['user_id'],
            'product_id' => $data['product_id'] ,
            'product_variation_id' => $data['product_variation_id'] ,
            'qty' =>  $data['qty'] ,
        ];

        $cartValue = GuestCart::where('product_id', $data['product_id'])->where('product_variation_id', $data['product_variation_id'])->where('user_id',$data['user_id'])->first();
        if( $cartValue){

            $create['qty'] = $cartValue['qty'] + 1;
            $cartUpdate = $cartValue->update($create);
            $cart = GuestCart::where('id',$cartValue->id)->first();
            $cartData = (new GuestCartService)->cartDetail($cart);
           return  $cartData;

        }else{

            #create

            $cart = GuestCart::create($create);
            $cart = GuestCart::where('id',$cart->id)->first();
            $cartData = (new GuestCartService)->cartDetail($cart);
            return  $cartData;

        }




    }

    public function updateCart($data)
    {
                
        foreach($data as $value){

        
            $cart = GuestCart::where('id', $value['cart_id'])->first();

            $update['qty'] = $value['qty'];
            $cartUpdate = GuestCart::where('id', $value['cart_id'])->update($update);
            $cart = GuestCart::where('id', $value['cart_id'])->first();
            $cartData = (new GuestCartService)->cartDetail($cart);
            

        }
            return  $cartData;
    
        if ($cartData) {

            return response()->json($cartData, 200);
        } else {

            return response()->json('Something went wrong!', 404);
        }
       
    }

    public function cartDetail($cart){
     
        $productDetail = Product::where('id', $cart->product_id)->with('cartCategory.parentCategory')->first(['name', 'featured_image', 'route', 'category_id']);
        $productVariant = ProductVariation::where('id', $cart->product_variation_id)->with('productVariationName.productVariationValues.variant')->first(['id', 'code', 'lower_price', 'upper_price', 'limit']);
        $quantity =  $cart->qty;

        #Check Limit For upper Price and Lower Price
            $limit = $productVariant['limit'];
            if ($quantity > $limit) {

                $price = $productVariant['lower_price'];
                $updateCart['unit_price'] = $productVariant['lower_price'];
            } else {

                $price = $productVariant['upper_price'];
                $updateCart['unit_price'] = $productVariant['upper_price'];
            }

        $updateCart['total'] = $quantity * $price;
        $cartUpdated = GuestCart::where('id', $cart->id)->update($updateCart);

        #Creating Final Amount 
            $cartPrice = GuestCart::where('user_id', $cart->user_id)->pluck('total');
            $finalAmount = $cartPrice->sum();

        
        $cartCalculation =   [
            'user_id' => $cart->user_id,
            'total' => $finalAmount,
            'sub_total' =>  $finalAmount,
            'decimal_amount' =>  $finalAmount * 100

        ];

        if ($cartcal = GuestCartCalculation::where('user_id', $cart->user_id)->exists()) {

            GuestCartCalculation::where('user_id', $cart->user_id)->update($cartCalculation);

            $cartTotal = GuestCartCalculation::where('user_id', $cart->user_id)->first();
            $discount = $cartTotal['discounted_price'];
            
            if($cartTotal['sub_total'] > 2000){
                
                    $update['shipping_charges'] = "Free";
                    $update['decimal_amount'] = $cartTotal['total'] * 100.00;
                    $cartTotal->update($update);
                    // if($discount != null){
                    //     $update['total']  = $cartTotal['total'] - $cartTotal['discounted_price'];
                    //     $cartTotal->update($update);
                        
                    // }
                    $cartTotal = GuestCartCalculation::where('user_id',$cart->user_id)->first();
                    return response()->json($cartTotal);

            }else{
                

                    $update['shipping_charges']  = 200;
                    $update['total']  = $cartTotal['sub_total'] + 200;
                    $update['decimal_amount'] = $update['total'] * 100.00;
                    $cartTotal->update($update);
                    // if($discount != null){
                    //     $update['total']  = $cartTotal['total'] - $cartTotal['discounted_price'];
                    //     $cartTotal->update($update);
                        
                    // }
                    $cartTotal = GuestCartCalculation::where('user_id',$cart->user_id)->first();
                        return response()->json($cartTotal);


            }
        } else {


            $cartTotal = GuestCartCalculation::firstOrcreate($cartCalculation);
            $discount = $cartTotal['discounted_price'];
            
            if($cartTotal['sub_total'] > 2000){
                
                    $update['shipping_charges'] = "Free";
                    $update['decimal_amount'] = $cartTotal['total'] * 100.00;
                    $cartTotal->update($update);
                    // if($discount != null){
                    //     $update['total']  = $cartTotal['total'] - $cartTotal['discounted_price'];
                    //     $cartTotal->update($update);
                        
                    //}
                    $cartTotal = GuestCartCalculation::where('user_id',$cart->user_id)->first();
                    return response()->json($cartTotal);

            }else{
                

                    $update['shipping_charges']  = 200;
                    $update['total']  = $cartTotal['sub_total'] + 200;
                    $update['decimal_amount'] = $update['total'] * 100.00;
                    $cartTotal->update($update);
                    // if($discount != null){
                    //     $update['total']  = $cartTotal['total'] - $cartTotal['discounted_price'];
                    //     $cartTotal->update($update);
                        
                    // }
                    $cartTotal = GuestCartCalculation::where('user_id',$cart->user_id)->first();
                        return response()->json($cartTotal);


            }
        }

        $decimaAmount = $finalAmount * 100;
        $data['products'] = $productDetail;
        $data['variation'] = $productVariant;
        $data['quantity'] = $quantity;
        $data['total'] = $finalAmount;
        $data['decimal_amount'] = $decimaAmount;
        return $data;


    }

    public function removeCart($id){

       
        $cart = GuestCart::where('id',$id)->first();

        
        $userId = $cart['user_id'];
        
        $cartTotal = GuestCartCalculation::where('user_id',$userId)->first();
        $discount = $cartTotal['discounted_price'];
        $update['total'] =  $cartTotal['total'] - $cart['total'];
        $update['sub_total'] =  $cartTotal['sub_total'] - $cart['total'];
        $update['decimal_amount'] =  $update['total'] * 100;

        $cartTotal = GuestCartCalculation::where('user_id',$cart->user_id)->update($update);
        $cartCalculation = GuestCartCalculation::where('user_id',$cart->user_id)->first();

        if($cartCalculation['sub_total'] != 0){
            

            if($cartCalculation['sub_total'] > 2000){

                $update['shipping_charges'] = "Free";
                $cartTotal = GuestCartCalculation::where('user_id',$cart->user_id)->update($update);
                // if($discount != null){
                //     $update['total']  = $cartCalculation['total'] - $cartCalculation['discounted_price'];
                //     $cartTotal = GuestCartCalculation::where('user_id',$cart->user_id)->update($update);
                    
                // }

                GuestCart::where('id',$id)->delete();
                return response()->json('Cart Value Removed');

            }else{
                
                $update['shipping_charges'] = 200;
                $update['total']  = $cartCalculation['sub_total'] + 200;
                $update['decimal_amount'] = $update['total'] * 100.00;
                $cartTotal = GuestCartCalculation::where('user_id',$cart->user_id)->update($update);
                // if($discount != null){
                //     $update['total']  = $cartCalculation['total'] - $cartCalculation['discounted_price'];
                //     $cartTotal = GuestCartCalculation::where('user_id',$cart->user_id)->update($update);
                //     //$cartTotal = CartCalculation::where('user_id',$cart->user_id)->first();
                    
                // }

                GuestCart::where('id',$id)->delete();
                return response()->json('Cart Value Removed');

            }

            

        }else{


            $cart = GuestCartCalculation::where('user_id',$userId)->delete();
            GuestCart::where('id',$id)->delete();
            return response()->json('No Product Fount');


        }

    }

}

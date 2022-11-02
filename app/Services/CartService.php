<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\ProductVariation;

use App\Models\CartCalculation;
use App\Models\GuestCart;
use App\Models\GuestCartCalculation;
use App\Models\Product;
use DB;



class CartService
{

    public function addToCart($data)
    {

        $create = [
            'user_id' =>  $data['user_id'],
            'product_id' => $data['product_id'],
            'product_variation_id' => $data['product_variation_id'],
            'qty' =>  $data['qty'],
        ];

        $cartValue = Cart::where('product_id', $data['product_id'])->where('product_variation_id', $data['product_variation_id'])->where('user_id', $data['user_id'])->first();
        if ($cartValue) {

            $create['qty'] =  $data['qty'];
            $cartUpdate = $cartValue->update($create);
            $cart = Cart::where('id', $cartValue->id)->first();
            $cartData = (new CartService)->cartDetail($cart);
            return  $cartData;
        } else {

            #create

            $cart = Cart::create($create);
            $cart = Cart::where('id', $cart->id)->first();
            $cartData = (new CartService)->cartDetail($cart);
            return  $cartData;
        }
    }

    public function updateCart($data)
    {

            
        foreach($data as $value){

        
            $cart = Cart::where('id', $value['cart_id'])->first();

            $update['qty'] = $value['qty'];
            $cartUpdate = Cart::where('id', $value['cart_id'])->update($update);
            $cart = Cart::where('id', $value['cart_id'])->first();
            $cartData = (new CartService)->cartDetail($cart);
           

        }
        return  $cartData;

        if ($cartData) {

            return response()->json($cartData, 200);
        } else {

            return response()->json('Something went wrong!', 404);
        }
      
    }

    public function cartDetail($cart)
    {
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
        $cartUpdated = Cart::where('id', $cart->id)->update($updateCart);

        #Creating Final Amount 
            $cartPrice = Cart::where('user_id', $cart->user_id)->pluck('total');
            $finalAmount = $cartPrice->sum();

        
        $cartCalculation =   [
            'user_id' => $cart->user_id,
            'total' => $finalAmount,
            'sub_total' =>  $finalAmount,
            'decimal_amount' => $finalAmount
        ];

        if ($cartcal = CartCalculation::where('user_id', $cart->user_id)->exists()) {
            CartCalculation::where('user_id', $cart->user_id)->update($cartCalculation);

            $cartTotal = CartCalculation::where('user_id', $cart->user_id)->first();
            $discount = $cartTotal['discounted_price'];
            
            if($cartTotal['sub_total'] > 2000){
                
                    $update['shipping_charges'] = "Free";
                    $update['decimal_amount'] = $cartTotal['total'] * 100.00;
                    $cartTotal->update($update);
                    $cartTotal = CartCalculation::where('user_id',$cart->user_id)->first();
                    return response()->json($cartTotal);

            }else{
                

                    $update['shipping_charges']  = 200;
                    $update['total']  = $cartTotal['sub_total'] + 200;
                    $update['decimal_amount'] = $update['total'] * 100.00;
                    $cartTotal->update($update);
                    $cartTotal = CartCalculation::where('user_id',$cart->user_id)->first();
                        return response()->json($cartTotal);


            }
        } else {

            $cartTotal = CartCalculation::firstOrcreate($cartCalculation);

            
            $discount = $cartTotal['discounted_price'];
            
            if($cartTotal['sub_total'] > 2000){
                
                    $update['shipping_charges'] = "Free";
                    $update['decimal_amount'] = $cartTotal['total'] * 100.00;
                    $cartTotal->update($update);
                    $cartTotal = CartCalculation::where('user_id',$cart->user_id)->first();
                    return response()->json($cartTotal);

            }else{
                

                    $update['shipping_charges']  = 200;
                    $update['total']  = $cartTotal['sub_total'] + 200;
                    $update['decimal_amount'] = $update['total'] * 100.00;
                    $cartTotal->update($update);
                    $cartTotal = CartCalculation::where('user_id',$cart->user_id)->first();
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


        $cart = Cart::where('id',$id)->first();
        $userId = $cart['user_id'];
        
        $cartTotal = CartCalculation::where('user_id',$userId)->first();
        $update['total'] =  $cartTotal['total'] - $cart['total'];
        $update['sub_total'] =  $cartTotal['sub_total'] - $cart['total'];
        $update['decimal_amount'] =  $update['total'] * 100;

        $cartTotal = CartCalculation::where('user_id',$cart->user_id)->update($update);
        $cartCalculation = CartCalculation::where('user_id',$cart->user_id)->first();

        if($cartCalculation['sub_total'] != 0){
            

            if($cartCalculation['sub_total'] > 2000){

                $update['shipping_charges'] = "Free";
                $cartTotal = CartCalculation::where('user_id',$cart->user_id)->update($update);
                Cart::where('id',$id)->delete();
                return response()->json('Cart Value Removed');

            }else{
                
                $update['shipping_charges'] = 200;
                $update['total']  = $cartCalculation['sub_total'] + 200;
                $update['decimal_amount'] = $update['total'] * 100.00;
                $cartTotal = CartCalculation::where('user_id',$cart->user_id)->update($update);
                Cart::where('id',$id)->delete();
                return response()->json('Cart Value Removed');

            }

            

        }else{


            $cart = CartCalculation::where('user_id',$userId)->delete();
            Cart::where('id',$id)->delete();
            return response()->json('No Product Fount');


        }


    }


 public function guestCartToUserCart($guest_id, $user_id){
       
        try {
            DB::beginTransaction();
               
                $guestCart = GuestCart::where('user_id', $guest_id)->get();
               
                    foreach ($guestCart as $guest) {
    
                        $cart = Cart::create([
                            'user_id' => $user_id,
                            'guest_id' => $guest_id,
                            'product_id' => $guest->product_id,
                            'product_variation_id' => $guest->product_variation_id,
                            'qty' => $guest->qty,
                            'unit_price' => $guest->unit_price,
                            'total' => $guest->total
                        ]);
                        
                    }
    
                    $guestCartCalculation = GuestCartCalculation::where('user_id', $guest_id)->first();
    
                    CartCalculation::create([
                        'user_id' => $user_id,
                        'guest_id' => $guest_id,
                        'coupon' => $guestCartCalculation->coupon,
                        'discounted_price' => $guestCartCalculation->discounted_price,
                        'shipping_charges' => $guestCartCalculation->shipping_charges,
                        'decimal_amount' => $guestCartCalculation->decimal_amount,
                        'total' => $guestCartCalculation->total,
                        'sub_total' => $guestCartCalculation->sub_total
                    ]);
               

            //   $guestCart->destroy();
            //   $guestCartCalculation->destroy();

            DB::commit();
             return true;

        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}

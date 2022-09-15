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

            $create['qty'] = $cartValue['qty'] + 1;

            $cartUpdate = $cartValue->update($create);
            $cart = Cart::where('id', $cartValue->id)->first();
            $cartData = (new CartService)->cartDetail($cart);
            return $cartData;
        } else {

            #create

            $cart = Cart::create($create);
            $cart = Cart::where('id', $cart->id)->first();
            $cartData = (new CartService)->cartDetail($cart);
            return $cartData;
        }
    }

    public function incrementQty($data)
    {


        $cart = Cart::where('id', $data['cart_id'])->first();

        $update['qty'] = $data['qty'] + $cart->qty;
        $cartUpdate = Cart::where('id', $data['cart_id'])->update($update);
        $cart = Cart::where('id', $data['cart_id'])->first();
        $cartData = (new CartService)->cartDetail($cart);


        if ($cartData) {

            return response()->json($cartData, 200);
        } else {

            return response()->json('Something went wrong!', 404);
        }
    }

    public function cartDetail($cart)
    {

        try {

            //DB::beginTransaction();
            $productDetail = Product::where('id', $cart->product_id)->with('cartCategory.parentCategory')->first(['name', 'featured_image', 'route', 'category_id']);
            $productVariant = ProductVariation::where('id', $cart->product_variation_id)->with('productVariationName.productVariationValues.variant')->first(['id', 'code', 'lower_price', 'upper_price', 'limit']);
            $quantity =  $cart->qty;
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
            $cartPrice = Cart::where('user_id', $cart->user_id)->pluck('total');
            $finalAmount = $cartPrice->sum();


            $cartCalculation =   [
                'user_id' => $cart->user_id,
                'total' => $finalAmount,
                'sub_total' =>  $finalAmount,
                'decimal_amount' =>  $finalAmount * 100

            ];
            if ($cartcal = CartCalculation::where('user_id', $cart->user_id)->exists()) {

                CartCalculation::where('user_id', $cart->user_id)->update($cartCalculation);
            } else {


                $cartCalculation = CartCalculation::create($cartCalculation);
            }

            //DB::commit();
            $data['products'] = $productDetail;
            $data['variation'] = $productVariant;
            $data['quantity'] = $quantity;
            $data['total'] = $finalAmount;

            return $data;
        } catch (\Exception $e) {

            //DB::rollBack();
            return response()->json(['Cart not updated.', 'stack' => $e], 500);
        }
    }


    public function guestCartToUserCart($guest_id, $user_id)
    {
        try {
            DB::beginTransaction();
            $guestCart = GuestCart::where('user_id', $guest_id)->get();
            foreach ($guestCart as $guest) {
                // return $guest;
                $cart =Cart::create([
                    'user_id' => $user_id,
                    'product_id' => $guest->product_id,
                    'product_variation_id' => $guest->product_variation_id,
                    'qty' => $guest->qty,
                    'unit_price' => $guest->unit_price,
                    'total' => $guest->total

                ]);
                $guest->delete();
            }

            $guestCartCalculation = GuestCartCalculation::where('user_id', $guest_id)->firstOrFail();

            CartCalculation::create([
                'user_id' => $user_id,
                'coupon' => $guestCartCalculation->coupon,
                'discounted_price' => $guestCartCalculation->discounted_price,
                'shipping_charges' => $guestCartCalculation->shipping_charges,
                'decimal_amount' => $guestCartCalculation->decimal_amount,
                'total' => $guestCartCalculation->total,
                'sub_total' => $guestCartCalculation->sub_total
            ]);

            // $guestCart->delete();
           $guestCartCalculation->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            // return response()->json($e, 400);
            DB::rollBack();
            return false;
        }
    }
}

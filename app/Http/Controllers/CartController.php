<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\ProductVariation;
use App\Models\User;
use App\Models\Product;
use App\Models\Variation;
use App\Models\VariationValues;
use App\Services\CartService;
use Auth;

use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index($id)
    {
        try{
            $cart = Cart::where('user_id',$id)->get();
            $product_ids = $cart->pluck('product_id');
            $variation_ids = $cart->pluck('variation_id');

            $productData =  Product::with(['product_variations.variation_items' => function($q) use($variation_ids) {
                $q->whereIn('variation_id', '=', $variation_ids );
            }])->
            whereIn('id', $product_ids)->get();
            return $productData;
            foreach ($productData['variations'] as $variant){
                $data = ProductPivotVariation::where('product_variation_id', $variant->id)->pluck('variation_value_id');
                $variant['variationItems'] = $data;
            }

            if($cart->isEmpty()){
                 return response()->json([] , 200);
            }
            return response()->json($cart, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }


    public function store(Request $request)
    {
        try{

            $data = $request->all();
            $cart = CartService::addToCart($data);
            $cart = Cart::where('id',$cart->id)->first();
            $cartData = $this->cartData($cart);

            if($cart){

                return response()->json($cartData , 200);

            }else{

                return response()->json('Something went wrong!', 404);
            }
        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Cart Value Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }


    public function removeCart($id)
    {
        $cart = Cart::where('id',$id)->delete();
        if($cart){
            return response()->json('Cart has been deleted.' , 200);
        }
    }


    public function clearAllCart($id)
    {
        $cart = Cart::where('user_id',$id)->delete();
        if($cart){
            return response()->json('Cart has been deleted.' , 200);
        }
    }


    public function incrementQty(Request $request){

        $data['qty'] = $request['qty'];
        $cartUpdate = Cart::where('id',$request->cart_id)->update($data);
        $cart = Cart::where('id',$request->cart_id)->first();
        $cartData = $this->cartData($cart);

        if($cart){

            return response()->json($cartData , 200);

        }else{

            return response()->json('Something went wrong!', 404);
        }

    }

    public function cartData($cart){
        $productDetail = Product::where('id',$cart->product_id)->with('cartCategory')->first(['name','featured_image','route','category_id']);
        $productVariant = ProductVariation::where('id',$cart->product_variation_id)->first('upper_price');
        $variationValue = VariationValues::where('id',$cart->variation_value_id)->first('name');
        $quantity =  $cart->qty;
        $price = $productVariant['upper_price'];
        $finalPrice = $quantity * $price;
        $data = [
            'quantity' => $quantity,
            'productDetail'=> $productDetail ,
            'variationValue' => $variationValue,
            'price' => $finalPrice
        ];
        return $data;


    }


}

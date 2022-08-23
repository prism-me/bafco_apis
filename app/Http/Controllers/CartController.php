<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartCalculation;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\VariationValues;
use App\Services\CartService;
use Auth;
use DB;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index($id)
    {
        try{

            $cart = Cart::where('user_id',$id)->get();

            if(!blank($cart)){

                $i=0;
                foreach($cart as $cartValue){
                    $data[$i]['productData'] = Product::where('id',$cartValue->product_id)->with('productCategory.parentCategory')->get(['name','id', 'route' ,'category_id']);
                    $data[$i]['variation'] = ProductVariation::where('id',$cartValue->product_variation_id)->get(['product_id' , 'images']);
                    $data[$i]['variation_value'] = VariationValues::where('id',$cartValue->variation_value_id)->get(['id' , 'variation_id','name','type_value']);
                    $data[$i]['qty'] = $cartValue->qty;
                    $data[$i]['total'] = $cartValue->total;
                    $i++;
                }

                return $data;

                return response()->json($cart, 200);


            }else{

                return response()->json('No Data Found', 404);
            }


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

        try {

            DB::beginTransaction();
                $productDetail = Product::where('id',$cart->product_id)->with('cartCategory.parentCategory' )->first(['name','featured_image','route','category_id']);
                $productVariant = ProductVariation::where('id',$cart->product_variation_id)->first(['id','code','upper_price']);
                $variationValue = VariationValues::where('id',$cart->variation_value_id)->first(['id','variation_id','name']);
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

            DB::commit();
                $data = $productDetail;
                $data['variation'] = $productVariant;
                $data['variationValue'] = $variationValue;
                $data['quantity'] = $quantity;
                $data['total'] = $finalPrice;

            return $data;

        } catch (\Exception $e) {

            DB::rollBack();
                return response()->json(['Product is not added.', 'stack' => $e], 500);
        }



    }



}

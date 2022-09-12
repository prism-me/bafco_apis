<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartCalculation;
use App\Models\Category;
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

                    $data[$i]['cart'] = Cart::where('id',$cartValue->id)->get(['id']);
                    $data[$i]['productData'] = Product::where('id',$cartValue->product_id)->with('productCategory.parentCategory')->get(['name','id', 'route' ,'category_id']);
                    $data[$i]['variation'] = ProductVariation::where('id',$cartValue->product_variation_id)->with('productVariationName.productVariationValues.variant')->get(['id','product_id' , 'upper_price','in_stock','images']);
                    $data[$i]['qty'] = $cartValue->qty;
                    $data[$i]['total'] = $cartValue->total;
                    $i++;
                }


                return response()->json($data, 200);


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


            if($cart){

                return response()->json($cart , 200);

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
        $cart = Cart::where('id',$id)->first();
        $cartCalc = CartCalculation::where('user_id',$cart['user_id'])->first();

        $update['total'] =  $cartCalc['total'] - $cart['unit_price'];
        $update['sub_total'] =  $cartCalc['sub_total'] - $cart['unit_price'];
        $cart = CartCalculation::where('user_id',$cart['user_id'])->update($update);
        Cart::where('id',$id)->delete();

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

        try{

            $data = $request->all();
            $cart = CartService::incrementQty($data);

            if($cart){

                return response()->json($cart , 200);

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


    public function show($id){

        $cart = Cart::where('id',$id)->first();
        $productData = Product::where('id',$cart['product_id'])->with('category.parentCategory')->first();
        $productVariation =  ProductVariation::where('id',$cart['product_variation_id'])->with('productVariationName.productVariationValues')->first();
        $product = [  $productData , $productVariation ,$cart];
        return response()->json($product);

    }


    public function cartTotal($id){

        $cartTotal = CartCalculation::where('user_id',$id)->first();

        if($cartTotal['sub_total'] > 2000){

            $update['shipping_charges'] = "Free";
            $update['decimal_amount'] = $cartTotal['total'] * 100.00;
            CartCalculation::where('user_id',$id)->update($update);
            $cartTotal = CartCalculation::where('user_id',$id)->first();
            return response()->json($cartTotal);

        }else{

            if($cartTotal['shipping_charges'] == NULL){

                $update['shipping_charges']  = 200;
                $update['total']  = $cartTotal['total'] + 200;
                $update['decimal_amount'] = $cartTotal['total'] * 100.00;
                CartCalculation::where('user_id',$id)->update($update);
                $cartTotal = CartCalculation::where('user_id',$id)->first();
                return response()->json($cartTotal);

            }elseif($cartTotal['shipping_charges'] == "Free"){

                $update['shipping_charges']  = 200;
                $update['total']  = $cartTotal['total'] + 200;
                $update['decimal_amount'] = $cartTotal['total'] * 100.00;
                CartCalculation::where('user_id',$id)->update($update);
                $cartTotal = CartCalculation::where('user_id',$id)->first();
                return response()->json($cartTotal);

            }else{

                return response()->json($cartTotal);

            }

        }

    }






}

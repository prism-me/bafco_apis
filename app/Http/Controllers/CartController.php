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
        $product = [  $productData , $productVariation ];
        return response()->json($product);



    }


    public function cartTotal($id){

        $subTotal = CartCalculation::where('user_id',$id)->first();
        return response()->json($subTotal);


    }





}

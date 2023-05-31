<?php

namespace App\Http\Controllers;

use App\Models\GuestCart;
use App\Models\GuestCartCalculation;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Services\GuestCartService;
use Illuminate\Http\Request;

class GuestCartController extends Controller
{
    public function index($id)
    {
        try{

            $cart = GuestCart::where('user_id',$id)->get();

            if(!blank($cart)){

                $i=0;
                foreach($cart as $cartValue){

                    $data[$i]['cart'] = GuestCart::where('id',$cartValue->id)->get(['id']);
                    $data[$i]['productData'] = Product::where('id',$cartValue->product_id)->with('productCategory.parentCategory')->get(['name','id', 'route' ,'category_id']);
                    $data[$i]['variation'] = ProductVariation::where('id',$cartValue->product_variation_id)->with('productVariationName.productVariationValues.variant')->get(['id','product_id' , 'upper_price','in_stock','images']);
                    $data[$i]['qty'] = $cartValue->qty;
                    $data[$i]['total'] = $cartValue->total;
                    $i++;
                }


                return response()->json($data, 200);


            }else{

                return response()->json(['error' => 'No Data Found', 200]);
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
            $cart = GuestCartService::addToCart($data);


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
        try{

            $cart = GuestCartService::removeCart($id);
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


    public function clearAllCart($id)
    {
        $cart = GuestCart::where('user_id',$id)->delete();
        if($cart){
            return response()->json('Cart has been deleted.' , 200);
        }
    }


    public function updateCart(Request $request){

        try{

            $data = $request->all();
            $cart = GuestCartService::updateCart($data);

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

        $cart = GuestCart::where('id',$id)->first();
        $productData = Product::where('id',$cart['product_id'])->with('category.parentCategory')->first();
        $productVariation =  ProductVariation::where('id',$cart['product_variation_id'])->with('productVariationName.productVariationValues')->first();
        $product = [  $productData , $productVariation ,$cart];
        return response()->json($product);

    }


    public function cartTotal($id){

        try {
            
            $cartTotal = GuestCartCalculation::where('user_id',$id)->first();
            return $cartTotal;
          
               
        } catch (\Exception $e) {

            return response()->json(['Cart not updated.', 'stack' => $e], 500);
        }

    }
}

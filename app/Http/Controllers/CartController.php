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
        try{
            $cart = CartService::removeCart($id);
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

        try {
            
            $cartTotal = CartCalculation::where('user_id',$id)->first();
            return $cartTotal;
          
               
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json(['Cart not updated.', 'stack' => $e], 500);
        }

    }


    public function getGuestCart(Request $request){

        $guest_id = $request['user_id'];
        $status = $request['status'];
        $user =  auth()->user();
        $user_id = $user['id'];
        if($status == true){
            $cart = CartService::guestCartToUserCart($guest_id,$user_id);
            return response()->json('success');
        }else{

            return  response()->json("Something Went wrong");
        }

    }






}

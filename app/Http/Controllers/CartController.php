<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\User;
use Auth;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        try{
            $user =  auth()->user()->id;
            $cart = Cart::where('user_id',$user)->get();
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
            $user =  auth()->user()->id;

            $data = [ 
                    'user_id' =>  $user,
                    'ip' => $request->ip ,
                    'mac' =>  $request->mac ,
            ];
        

            if(Cart::where('id', $request->id)->exists()){ 

                #update
                $cart = Cart::where('id', $request->id)->update($data);

            }else{

                #create
                $cart = Cart::create($data);
            }
            if($cart){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Cart Value Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        } 
    }


    public function removeCart(Request $request)
    {
        $cart = Cart::where('id',$request->id)->delete();
        if($cart){
            return response()->json('Cart has been deleted.' , 200);
        }
    }


    public function clearAllCart()
    {
        $user =  auth()->user()->id;
        $cart = Cart::where('user_id',$user)->delete();
        if($cart){
            return response()->json('Cart has been deleted.' , 200);
        }
    }


}

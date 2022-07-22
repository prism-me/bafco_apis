<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Auth;


class WishlistController extends Controller
{


    public function index()
    {
        
        $wishlist = Wishlist::where('user_id',auth()->user()->id)->with('wishlistProduct.getWishlistvariations')->get();
        return $wishlist;

    }

 

    public function store(Request $request)
    {
        $data['user_id'] =  isset( $request->user_id ) ? $request->user_id:'';
        $data['product_id'] = isset( $request->product_id )? $request->product_id:'' ;
        $data['variation_id'] = isset( $request->variation_id )? $request->variation_id:'' ;
        try{

           if(Wishlist::where('id', $request->id)->exists()){ 
            //update
                $wishlist = Wishlist::where('id',$request->id)->update($data);
           }else{
            // create
                $wishlist = Wishlist::create($data);
           }
           if($wishlist){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Wishlist Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch(QueryException $exception){
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine() ], 400);   
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        } 
    }

    
    public function show($id, Wishlist $wishlist)
    {
         
        try{
            $wishlist = Wishlist::where('id',$id)->get();
         
            $data = Product::where('id', $wishlist->id)->with('variation')->get();
            return $data;
            if($wishlist->isEmpty()){
                 return response()->json([] , 200);
            }
            return response()->json($wishlist, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
    }



    public function destroy($id)
    {
        $wishlist = Wishlist::where('id',$id)->delete();
        if($wishlist){
            return response()->json('Wishlist has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }
}

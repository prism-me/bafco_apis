<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\VariationValues;
use App\Models\Wishlist;
use App\Services\WishlistService;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


class WishlistController extends Controller
{


    public function index($id)
    {
        $wishlist = Wishlist::where('user_id',$id)->get();

        if(!blank($wishlist)) {

            $i = 0;
            foreach ($wishlist as $wishlistValue) {
                $data[$i]['productData'] = Product::where('id', $wishlistValue->product_id)->with('productCategory.parentCategory')->get(['name', 'id', 'route', 'category_id']);
                $data[$i]['variation'] = ProductVariation::where('id',$wishlistValue->product_variation_id)->get(['id','product_id' , 'images']);
                $i++;
            }

            return $data;


        }



    }

    public function store(Request $request)
    {
        try{

            $data = $request->all();
            $wishlist = WishlistService::addToWishlist($data);


            if($wishlist){

                return response()->json($wishlist , 200);

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


    public function removeWishlist($id)
    {
        $wishlist = Wishlist::where('id',$id)->delete();

        if($wishlist){
            return response()->json('Cart has been deleted.' , 200);
        }
    }





}

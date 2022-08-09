<?php

namespace App\Http\Controllers;

use App\Models\ProductVariation;
use App\Models\VariationValues;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\WishlistService;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;



class WishlistController extends Controller
{


    public function index()
    {
        dd('hi');

//        try{
//
//            $wishlist = Wishlist::where('user_id',$id)->get();
//            $i=0;
//            foreach($wishlist as $wishlistValue){
//                $data[$i]['productData'] = Product::where('id',$wishlistValue->product_id)->get(['name','id', 'route' ,'category_id']);
//                $data[$i]['variation'] = ProductVariation::where('product_id',$wishlistValue->product_id)->get(['id' , 'product_id' , 'images' , ]);
//                $data[$i]['category'] = Category::where('id',$data[$i]['productData'][0]['category_id'])->with('parentCategory')->get('id' ,'name', 'parent_id','route','images');
//                $i++;
//            }
//
//            return $data;
//
//
//            if($cartData->isEmpty()){
//                return response()->json([] , 200);
//            }
//            return response()->json($cart, 200);
//        }
//        catch (\Exception $exception) {
//            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
//        }

    }

    public function store(Request $request)
    {
        try{

            $data = $request->all();
            $wishlist = WishlistService::addToWishlist($data);
            $wishlist = Wishlist::where('id',$wishlist->id)->first();
            $wishlistData = $this->WishlistData($wishlist);

            if($wishlist){

                return response()->json($wishlistData , 200);

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


    public function WishlistData($wishlist){

        $productDetail = Product::where('id',$wishlist->product_id)->with('cartCategory')->first(['name','featured_image','route','category_id']);
        $productVariant = ProductVariation::where('id',$wishlist->product_variation_id)->first('upper_price');
        $variationValue = VariationValues::where('id',$wishlist->variation_value_id)->first('name');

        $data = [
            'productDetail'=> $productDetail ,
            'variationValue' => $variationValue,
        ];
        return $data;


    }


}

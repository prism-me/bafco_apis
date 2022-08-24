<?php

namespace App\Services;
use App\Models\Wishlist;
use App\Models\Category;
use App\Models\ProductVariation;
use DB;

use App\Models\Product;



class WishlistService {

    public function addToWishlist($data){

        $create = [
            'user_id' =>  $data['user_id'],
            'product_id' => $data['product_id'] ,
            'product_variation_id' =>  $data['product_variation_id'] ,
        ];
        $wishlistValue = Wishlist::where('product_id', $data['product_id'])->where('product_variation_id', $data['product_variation_id'])->where('user_id',$data['user_id'])->first();


        if( $wishlistValue){

            $wishlist = Wishlist::where('id',$wishlistValue->id)->first();
            $wishlistData = (new WishlistService)->WishlistDetail($wishlist);
            return $wishlistData;

        }else{

            #create
            $wishlistCreate = Wishlist::create($create);
            $wishlist = Wishlist::where('id',$wishlistCreate->id)->first();
            $wishlistData = (new WishlistService)->WishlistDetail($wishlist);
            return $wishlistData;

        }



    }

    public function WishlistDetail($wishlist){

        try {

            DB::beginTransaction();
            $productDetail = Product::where('id',$wishlist->product_id)->with('cartCategory.parentCategory')->first(['name','featured_image','route','category_id']);
            $productVariant = ProductVariation::where('id',$wishlist->product_variation_id)->with('productVariationName.productVariationValues.variant')->first(['id','code','upper_price']);
            $data = $productDetail;
            $data['variation'] = $productVariant;


            DB::commit();

            return $data;


        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json(['Product is not added.', 'stack' => $e], 500);
        }

    }
}

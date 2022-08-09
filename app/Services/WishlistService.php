<?php

namespace App\Services;
use App\Models\Wishlist;

use App\Models\Product;



class WishlistService {

    public function addToWishlist($data){

        $create = [
            'user_id' =>  $data['user_id'],
            'product_id' => $data['product_id'] ,
            'product_variation_id' =>  $data['product_variation_id'] ,
            'variation_value_id' =>  $data['variation_value_id']

        ];
        $wishlistValue = Wishlist::where('product_id', $data['product_id'])->where('product_variation_id', $data['product_variation_id'])->where('user_id',$data['user_id'])->first();


        if( $wishlistValue){

            return $wishlistValue;

        }else{

            #create
            $wishlist = Wishlist::create($create);
            return $wishlist;


        }



    }
}

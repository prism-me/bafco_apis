<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\User;
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
        $wishlist = Wishlist::where('user_id', $id)->get();

        if (!blank($wishlist)) {

            $i = 0;
            foreach ($wishlist as $wishlistValue) {
                $data[$i]['wishlist'] = Wishlist::where('id', $wishlistValue->id)->get('id');
                $data[$i]['productData'] = Product::where('id', $wishlistValue->product_id)->with('productCategory.parentCategory')->get(['name', 'id', 'route', 'category_id']);
                $data[$i]['variation'] = ProductVariation::where('id', $wishlistValue->product_variation_id)->with('productVariationName.productVariationValues.variant')->get(['id', 'product_id', 'in_stock', 'upper_price', 'images']);
                $i++;
            }

            return $data;
        }
    }

    public function store(Request $request)
    {
        try {

            $data = $request->all();
            $wishlist = WishlistService::addToWishlist($data);


            if ($wishlist) {

                return response()->json($wishlist, 200);
            } else {

                return response()->json('Something went wrong!', 404);
            }
        } catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message' => 'Cart Value Not found.', 'line' => $exception->getLine()], 400);
        } catch (\Error $exception) {
            return response()->json(['ex_message' => $exception->getMessage(), 'line' => $exception->getLine()], 400);
        }
    }


    public function removeWishlist($id)
    {
        $wishlist = Wishlist::where('id', $id)->delete();

        if ($wishlist) {
            return response()->json('Cart has been deleted.', 200);
        }
    }


    public function show($id)
    {

        $wishlist = Wishlist::where('id', $id)->first();
        $productData = Product::where('id', $wishlist['product_id'])->with('category.parentCategory')->first();
        $productVariation =  ProductVariation::where('id', $wishlist['product_variation_id'])->with('productVariationName.productVariationValues')->first();
        $product = [$productData, $productVariation];
        return response()->json($product);
    }


    public function list()
    {

        $user_ids = Wishlist::pluck('user_id');

        $users = User::whereIn('id', $user_ids)->with('wishlist')->with('addressDetail:user_id,phone_number')->get();

        if (!blank($users)) {

            $wish = [];
            $r = 0;
            foreach ($users as $user) {

                $i = 0;
                foreach ($user['wishlist'] as $wish) {
                    // $data[$i]['wishlist'] = Wishlist::where('id', $wishlistValue->id)->get('id');
                    $users[$r]['wishlist'][$i]['productData'] = Product::where('id', $wish->product_id)->with('productCategory.parentCategory')->get(['name', 'id', 'route', 'category_id']);
                    $users[$r]['wishlist'][$i]['variation'] = ProductVariation::where('id', $wish->product_variation_id)->with('productVariationName.productVariationValues.variant')->get(['id', 'product_id', 'in_stock', 'upper_price', 'images']);
                    $i++;
                }
                $r++;
            }

            return $users;
        }
    }
}

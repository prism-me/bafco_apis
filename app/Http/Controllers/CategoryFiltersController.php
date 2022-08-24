<?php

namespace App\Http\Controllers;


use App\Http\Resources\ProductVariation;
use App\Models\Category;
use App\Models\Product;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redis;

class CategoryFiltersController extends Controller
{

    public function CategoryFilterList(Category $category){

        $redis = Redis::connection();
        $redis->del('category_filters_list1');

        if(!$redis->get('category_filters_list1')){

            // $brands = Product::distinct()->where('category_id',$category->id)->pluck('brand');
            $variations =  DB::select("CALL CategoryFilterList('". $category->route ."')");

            $redis->set('category_filters_list1', [ "value" => $variations ]);

            return response()->json(['category_filters_list1' => $variations] , 200);

        }else{
            return response()->json(["category_filters_list2" , $redis->get('category_filter_list')] ,200);


        }

        // $brands = Product::distinct()->where('category_id',$category->id)->pluck('brand');
        // $variations =  DB::select("CALL CategoryFilterList('". $category->route ."')");

        // return response()->json(['brands' => $brands , 'variations' => $variations] , 200);

    }



    public function CategoryListFilteration(Request $request)
    {
        $brand = $request->brand;
        $route = $request->route;
        $max = $request->max;
        $min = $request->min;

        $category = Category::with(['parentCategory'])->where('route',$route)->first();

        #Brands
        if (isset($brand)) {

            $products = Category::with(['parentCategory'])->with(['products'=> function($query) use($brand,$category) {
                $query->where('brand','=', $brand)->where('category_id',$category->id)->with(['productvariations']);
            }])->where('id',$category->id)->get();

        }

        #Price Range
        if ( $min && $max !== null) {

            $productId =  Category::with(['parentCategory'])->with(['products.productvariations' => function ($range) use ($min, $max) {
                        $range->whereBetween('lower_price', [$min, $max]);
                        }])->where('id',$category->id);

            foreach($productId as $product){

                if(isset( $product['products'][0]['productvariations']['product_id']) !== null) {

                    $id = $product['products'][0]['productvariations']['product_id'];

                    $products = Category::with(['parentCategory'])
                                                        ->with(['products' => function ($productsID) use ($productId, $min, $max,$category,$id) {
                                                            $productsID->where('id', $id)

                                                        ->with(['productvariations' => function ($range) use ($min, $max, $productId ,$id) {
                                                            $range->whereBetween('lower_price', [$min, $max])
                                                                ->where('product_id', $id);
                                                        }]);
                                                                }])
                                                        ->where('id', $category->id)->get();
                }

            }

        }

        return  $products;





    }









}

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
        $categoryId = $request->category_id;
        $max = $request->max;
        $min = $request->min;
        $variationItems = $request->variationItems;

        $query = Product::where('category_id',$categoryId)->with('category.parent');
        if (isset($brand)) {

            $query->where('brand', $brand);
        }

        #Price Range
        if ($min && $max !== null) {
            $query->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
                ->whereBetween('lower_price', [$min,$max]);

        }else{

            $query->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id');
        }

        $products = $query->get();
        return new ProductVariation($products);

    }









}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductPivotVariation;
use App\Models\Variation;
use App\Models\Product;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class CategoryFiltersController extends Controller
{

    public function CategoryFilterList(Category $category)
    {

        $variations =  DB::select("CALL CategoryFilterList('" . $category->route . "')");
        return response()->json($variations, 200);

    }

    public function CategoryListFilteration(Request $request)
    {
        $brand = $request->brand;
        $route = $request->route;
        $max = $request->max;
        $min = $request->min;
        $color  = $request->color;

        $category = Category::where('route',$route)->first();


        if (!empty($brand) && isset($min) && $min !== null && $max !== null && $color !== null ) {
                #Brands && Price
                $productId = ProductPivotVariation::whereIn('variation_value_id', $color)->pluck('product_id')->unique();
                $products = Product::with('productCategory.parentCategory')
                    ->with(['productvariations' => function ($range) use ($min, $max) {
                        $range->whereBetween('upper_price', [$min, $max])->where('in_stock',1);
                    }])
                    ->whereHas('productvariations')
                    ->where('id', $category->id)
                    ->whereIn('brand', $brand)
                    ->whereIn('id', $productId)
                    ->first();

                if(!empty($products)){
                    $productFiltered =[];
                    $i=0;
                    foreach($products as $item){
                        if($item->productvariations){
                            $productFiltered[$i] = $item;
                            $i++;
                        }

                    }
                    unset($products);
                    $products = $productFiltered;
                    return response()->json($products);
                }else{

                    return response()->json('No Product Found');

                }


        } elseif (!empty($brand)) {

            #Brands
            $products = Product::with('productCategory.parentCategory')->whereIn('brand', $brand)
                    ->where('category_id', $category->id)
                    ->with(['productvariations']);
            return response()->json($products);

        } elseif (isset($min) && $min !== null && $max !== null) {

                $products =   Product::with('productCategory.parentCategory')->with(['productvariations' => function ($range) use ($min,$category, $max) {
                    $range->whereBetween('upper_price' , [$min, $max])->where('in_stock',1);
                }])
                ->whereHas('productvariations')->where('category_id',$category->id)
                ->get();
                $productFiltered =[];
                $i=0;
                foreach($products as $item){
                    if($item->productvariations){
                        $productFiltered[$i] = $item;
                    $i++;
                    }

                }
                unset($products);
                $products = $productFiltered;

                return response()->json($products);

        }elseif(!empty($color)){

            $productId  = ProductPivotVariation::whereIn('variation_value_id', $color)->pluck('product_id')->unique();


            $products =   Product::with('productCategory.parentCategory','productvariations')
                                    ->where('category_id',$category->id)
                                    ->whereIn('id',$productId)->get();

            return response()->json($products);

        }else {

            return response()->json('No Product Found', 404);
        }
    }
}

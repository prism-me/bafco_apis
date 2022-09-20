<?php

namespace App\Http\Controllers;

use App\Models\Category;
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



        $category = Category::with(['parentCategory'])->where('route', $route)->first();

//        $products = Category::with(['parentCategory'])
//            ->with(['products.productvariations.product_variation_values.variantValues' => function ($color) {
//                $color->where('route' , $color);
//
//            }])
//            ->whereHas('products.productvariations')
//            ->where('id', $category->id)
//            ->first();

        if (!empty($brand) && isset($min) && $min !== null && $max !== null) {

            #Brands && Price
            $products =  Category::with(['parentCategory'])->with(['products' => function ($query) use ($brand, $category, $min, $max) {
                $query->whereIn('brand', $brand)
                    ->with(['productvariations' =>  function ($range) use ($min, $max) {
                        $range->whereBetween('lower_price' , [$min, $max]);
                    }]);
            }])
                ->whereHas('products.productvariations')
                ->where('id', $category->id)
                ->first();

            $filteredNull = $products->products->whereNotNull('productvariations');
            unset($products->products);
            $products->products = $filteredNull;

            return response()->json($products);
        } elseif (!empty($brand)) {

            #Brands
            $products = Category::with(['parentCategory'])->with(['products' => function ($query) use ($brand, $category) {
                $query->whereIn('brand', $brand)
                    ->where('category_id', $category->id)
                    ->with(['productvariations']);
            }])
                ->where('id', $category->id)->first();

            return response()->json($products);
        } elseif (isset($min) && $min !== null && $max !== null) {
            $products = Category::with(['parentCategory'])
                ->with(['products.productvariations' => function ($range) use ($min, $max) {
                    $range->whereBetween('lower_price' , [$min, $max]);
                }])
                ->whereHas('products.productvariations')
                ->where('id', $category->id)
                ->first();
            $filteredNull = $products->products->whereNotNull('productvariations');
            unset($products->products);
            $products->products = $filteredNull;

            return response()->json($products);
        } else {

            return response()->json('No Product Found', 404);
        }
    }
}

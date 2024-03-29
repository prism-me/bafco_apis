<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductPivotVariation;
use App\Models\Variation;
use App\Models\product;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CategoryFiltersController extends Controller
{

    public function CategoryFilterList(Category $category)
    {

        $variations =  DB::select("CALL CategoryFilterList('" . $category->route . "')");
        // DB::enableQueryLog(); 
        
        $categories = Category::where('parent_id', $category->parent_id)->withCount(['products' => function($q){
            $q->where('status', 1);
        }])->get();
        // ->filter(function($category) { return $category['products']['status'] > 0; });
        
    //   return DB::getQueryLog();
        
        $brands = Product::distinct()->select('brand','category_id')->where('category_id',$category->id)->get();

        return response()->json(['categories' => $categories, 'variations' => $variations , 'brands' => $brands], 200);
    }

    public function CategoryListFilteration(Request $request)
    {
        $brand = $request->brand;
        $route = $request->route;
        $max = $request->max;
        $min = $request->min;
        $color  = $request->color;

        $category = Category::where('route', $route)->first();


        if (!empty($brand) && isset($min) && $min !== null && $max !== null && $color !== null) {
            #Brands && Price
            $productId = ProductPivotVariation::whereIn('variation_value_id', $color)->pluck('product_id')->unique();
            $products = Product::with('productCategory.parentCategory')
                ->with(['productvariations' => function ($range) use ($min, $max) {
                    $range->whereBetween('lower_price', [$min, $max]);
                }])
                ->whereHas('productvariations')
                ->where('id', $category->id)
                ->whereIn('brand', $brand)
                ->whereIn('id', $productId)
                ->first();

            if (!empty($products)) {
                $productFiltered = [];
                $i = 0;
                foreach ($products as $item) {
                    if ($item->productvariations) {
                        $productFiltered[$i] = $item;
                        $i++;
                    }
                }
                unset($products);
                $products = $productFiltered;
                return response()->json($products);
            } else {

                return response()->json('No Product Found');
            }
        } elseif (!empty($brand)) {

            #Brands
            $products = Product::with('productCategory.parentCategory')->whereIn('brand', $brand)
                ->where('category_id', $category->id)
                ->with(['productvariations']);
            return response()->json($products);
        } elseif (isset($min) && $min !== null && $max !== null) {

            $products =   Product::with('productCategory.parentCategory')->with(['productvariations' => function ($range) use ($min, $category, $max) {
                $range->whereBetween('lower_price', [$min, $max]);
            }])
                ->whereHas('productvariations')->where('category_id', $category->id)
                ->get();
            $productFiltered = [];
            $i = 0;
            foreach ($products as $item) {
                if ($item->productvariations) {
                    $productFiltered[$i] = $item;
                    $i++;
                }
            }
            unset($products);
            $products = $productFiltered;

            return response()->json($products);
        } elseif (!empty($color)) {

            $productId  = ProductPivotVariation::whereIn('variation_value_id', $color)->pluck('product_id')->unique();


            $products =   Product::with('productCategory.parentCategory', 'productvariations')
                ->where('category_id', $category->id)
                ->whereIn('id', $productId)->get();

            return response()->json($products);
        } else {

            return response()->json('No Product Found', 404);
        }
    }
}

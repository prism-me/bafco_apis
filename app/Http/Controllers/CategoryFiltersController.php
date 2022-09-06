<?php

namespace App\Http\Controllers;


use App\Models\Category;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CategoryFiltersController extends Controller
{

   public function CategoryFilterList(Category $category){

        $variations =  DB::select("CALL CategoryFilterList('". $category->route ."')");
        return response()->json($variations , 200);

    }



    public function CategoryListFilteration(Request $request)
    {
        $brand = $request->brand;
        $route = $request->route;
        $max = $request->max;
        $min = $request->min;

        $category = Category::with(['parentCategory'])->where('route', $route)->first();
        
        if($brand !== null && $min !== null && $max !== null ){
            $products =  Category::with(['parentCategory'])->with(['products' => function($query) use($brand,$category,$min, $max) {
                                $query->where('brand', '=', $brand)
                            ->with(['productvariations' =>  function ($range) use ($min, $max) {
                                $range->where('lower_price', '>=', $min)->where('upper_price', '<=', $max);
                                }]);
                            }])
                            ->whereHas('products.productvariations')
                            ->where('id',$category->id)
                            ->first();

            return response()->json($products);

        }elseif( isset($brand) && $brand !== null){
            #Brands
            $products = Category::with(['parentCategory'])->with(['products'=> function($query) use($brand,$category) {
                            $query->where('brand','=', $brand)
                            ->where('category_id',$category->id)
                            ->with(['productvariations']);
                        }])
                        ->where('id',$category->id)->first();

            return response()->json($products);

        }elseif($min && $max !== null){
            #Price Range
            $products = Category::with(['parentCategory'])
                        ->with(['products.productvariations' => function ($range) use ($min, $max) {
                            $range->where('lower_price', '>=', $min)->where('upper_price', '<=', $max);
                        }])
                            ->whereHas('products.productvariations')
                            ->where('id', $category->id)
                            ->first();
            return response()->json($products);

        }else {

            return response()->json('No Product Found',404);

        }

    }


}

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

        $categories = Category::where('parent_id', $category->parent_id)->withCount('products')->get(['name', 'route', 'products_count']);
        // return $categories;
        return response()->json(['categories' => $categories, 'variations' => $variations], 200);
    }

    public function CategoryListFilteration(Request $request)
    {
        $brand =  $request->brand;
        $route =  $request->route;
        $max =  $request->max ;
        $min =   $request->min;
        $color  = $request->color;
       
        
        $category = Category::where('route', $route)->first();
      

        $products = Product::with('productCategory.parentCategory')
                            ->when(!empty($request->brand), function($q) use ($brand,$category) {
                                $q->whereIn('brand', $brand);
                                
                            })

                            ->when(($request->min == 0 || $request->min > 0 ) &&  !empty($request->max) , function ($q) use ($min,$max) {

                                    $q->with('productvariations', function($q)  use ($min, $max) {

                                        $q->whereBetween('upper_price', [$min, $max]);
                                    });
                                })

                            ->when(!empty($request->color)  , function ($q)  use($color){
                                $productId = ProductPivotVariation::whereIn('variation_value_id', $color)->pluck('product_id')->unique();
                                $q->whereIn('id', $productId);

                            })
                            
                            ->whereHas('productvariations')
                            ->where('category_id',$category->id)
                            ->where('status',1)
                            ->when( count( $request->all()) === 0, function($q){
                                return response()->json([]);
                            })->get();
                            
                            if($products->count() > 0){

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

                                    return response()->json([]);
                                }
                            }else{

                                return response()->json([]);

                            }
    }

 
}

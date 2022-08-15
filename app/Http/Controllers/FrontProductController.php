<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPivotVariation;
use App\Models\ProductVaraition;
use App\Models\ProductVariation;
use App\Models\VariationValues;
use Illuminate\Http\Request;
use DB;
class FrontProductController extends Controller
{


    /* Home Page Top Selling Product */
        public function homeProductCategoryFilter($route)
    {
        if($route == 'all' ){

            $product = Product::with('variations.variation_items.variation_values')->get()->take(4);

            $data = $product;
        }
        else{
            //$productFilter = Category::with('products')->where('route',$route)->get()->take(4);

            $productFilter = Category::with('products.variations.variation_items.variation_values')->where('route',$route)->get()->take(4);
            $data =  $productFilter;

        }

        return response()->json($data);
    }
    /* ENd Home Page Top Selling Product*/

    /* Products Inner Page*/


        #Product Inner Category Listing
            public function frontProducts($route)
        {

            $products = Category::with('parentCategory','products.productvariations.productVariationName.productVariationValues')->where('route',$route)->first(['id','route','name','parent_id']);
            return response()->json($products);
        }

        #Product Detail Page
            public function productDetail($route)
        {
            //\DB::enableQueryLog();
            $productDetails =  Product::where('route', $route)->first();
            $headRest = VariationValues::whereIn('id',$productDetails->headrest )->get();
            $footRest = VariationValues::whereIn('id',$productDetails->footrest )->get();
            $product = [
                'product' => $productDetails,
                'headrest' => $headRest,
                'footrest' => $footRest,

            ];

            //$relatedProducts = Product::with('variations','productDetailCategory.parentCategory')->paginate(4);
            $relatedProducts = Product::with('productCategory','productvariations.productVariationName.productVariationValues')->where('category_id',$productDetails->category_id)->paginate(4);

            $randomProducts = Product::with('productCategory','productvariations.productVariationName.productVariationValues')->inRandomOrder()
                ->limit(4)
                ->paginate(4);

            $dimensions = ProductVariation::where('product_id',$productDetails->id)->get(['product_id', 'id','code','lc_code','height','depth','width' ,'images']);
            $i=0;
            foreach($dimensions as $value){
                $dimensions[$i]['images'] =     $value['images'][0];
                $i++;
            }

            if (isset($productDetails) && !empty($productDetails)) {
                $productVariationIdArr = $productDetails->variations()->pluck('id');

                #First Variation
                $productPivotListingSingle = ProductPivotVariation::whereIn('product_variation_id',$productVariationIdArr)->first();
                $productSingleVariation = Product::getProductDetail($productPivotListingSingle);
                $productPivotListings = ProductPivotVariation::whereIn('product_variation_id',$productVariationIdArr)->get();
                $productAllVariations  = $productPivotListings->transform(function($item){
                    $item->product_details = Product::getProductVariation($item);
                    return $item;
                })->all();

            }
            return response()->json([
                'single_product_details' => $product,
                'product_single_variation' => $productSingleVariation,
                'product_all_varitaions' => $productAllVariations,
                'related_products' => $relatedProducts,
                'random_purchase' => $relatedProducts,
                'dimensions' => $dimensions
            ]);
        }

        #Detail Page Filter

            #Filter Listing
            public function filterListing(Category $category){

                $brands = Product::distinct()->where('category_id',$category->id)->pluck('brand');
                $variations =  DB::select("CALL VariationNamesOnly('". $category->route ."')");

                return response()->json(['brands' => $brands , 'variations' => $variations] , 200);


            }

            #Filter Data
            public function productDetailVariationFilter(Request $request){

                $IDs = $request->all();
                $product_single_variation  = ProductPivotVariation::where('product_id',$IDs['product_id'])
                    ->where('product_variation_id',$IDs['product_variation_id'])
                    ->where('variation_value_id',$IDs['variation_value_id'])
                    ->get();
                dd($data);
                $productVariation  = ProductPivotVariation::where('product_variation_id',$IDs['product_variation_id'])->get('variation_value_id');
        //        $value  = Variation
        //        return response()->json([
        //            'product_single_variation' => $productVariation,
        //
        //        ]);


            }



    /* Category Product Inner  Page */

        public function category($route){

        $category = Category::where('route' , $route)->with('subcategoryProducts')->get(['id','name','route']);

       return response()->json($category);
    }

    /*End Category Filter On Inner Page */





}

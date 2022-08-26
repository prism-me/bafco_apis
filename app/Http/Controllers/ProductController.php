<?php

namespace App\Http\Controllers;

use App\Http\Requests\product\ProductRequest;
use App\Models\Product;
use App\Models\ProductPivotVariation;
use App\Models\ProductVariation;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{


    public function index()
    {
        try{
            // DB::enableQueryLog();

            return Product::with('variations','category','variations.variation_items')->where('status', 1)->get();


            // return DB::getQueryLog();
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }

    public function website_all(){
        try{

            DB::enableQueryLog();

            $variations = ProductVariation::with(['variation_name','variation_values'])->get();

            //return DB::getQueryLog();
            return $variations;
            // $products = Product::with('product_variations')->get();

            // foreach($products as $product){
            //     return $product->product_variations->product_variation_values();
            // }

            //  => function($query) {
            //    dd($query->product_variation_values());
            // }])->get();
            // if($products->isEmpty()){
            //     return response()->json('No Record Found.' , 404);
            // }
            // return response()->json($products, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }


    public function store(ProductRequest $request)
    {

        try{

            if(Product::where('id',$request->id)->exists()){

                $product = ProductService::updateProduct($request->all());
                return $product;

            }else{

                #create
                $product = ProductService::insertProduct($request->all());
                return $product;
            }

        }
         catch (\Error $exception) {
             return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }

    }


    public function show($id)
    {


        $productData =  Product::with('variations')->where('id',$id)->first();
        foreach ($productData['variations'] as $variant){
            $data = ProductPivotVariation::where('product_variation_id', $variant->id)->pluck('variation_value_id');
            $variant['variationItems'] = $data;
        }
        return response()->json($productData , 200);


//        if(!$product){
//            return response()->json('No Record Found.' , 404);
//        }
//        $product->category =  $product->category;
//        return response()->json($product , 200);
    }



    public function disableProducts(){

        try{

            return Product::with('variations','category','variations.variation_items')->where('status', 0)->get();

        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }


    public function changeStatus(Request $request , $id)
    {

        $data['status'] = $request->status;
        Product::where('id',$id)->update($data);
        return response()->json('Product Disabled Successfully!', 200);
    }




}

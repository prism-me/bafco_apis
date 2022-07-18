<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\CartService;
use App\Http\Requests\product\ProductRequest;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{   
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        // return substr(exec('getmac'), 0, 17); 
        //return $req->ip();

        try{
            // DB::enableQueryLog();
            
            return Product::with('variations','variations.variation_items')->get();

            
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

            if(!Product::where('route', $request->route)->exists()){ 
             //update
                 
             //$product = Product::where('route',$request->route)->update($request->all());
            }else{

             // create
             $product = ProductService::insertProduct($request->all());
            }
            return $product;
            if($product){

                 return  response()->json('Data has been saved.' , 200);
             }

        }
         catch (\Error $exception) {
             return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }   

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return Product::with('variations','variations.variation_items')->where('route', $product->route)->get();

        if(!$product){
            return response()->json('No Record Found.' , 404);
        }
        $product->category =  $product->category;
        return response()->json($product , 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}

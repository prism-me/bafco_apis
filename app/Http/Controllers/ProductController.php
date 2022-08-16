<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\VariationValues;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\CartService;
use App\Models\ProductPivotVariation;
use App\Http\Requests\product\ProductRequest;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{


    public function index()
    {
        // return substr(exec('getmac'), 0, 17);
        //return $req->ip();

        try{
            // DB::enableQueryLog();

            return Product::with('variations','category','variations.variation_items')->get();


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

            if(Product::where('id',$request->id)->exists() && Product::where('route',$request->route)->exists()){


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





    public function changeStatus(Request $request , $id)
    {

        $data['status'] = $request->status;
        Product::where('id',$id)->update($data);
        return response()->json('Product Disabled Successfully!', 200);
    }




}

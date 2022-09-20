<?php

namespace App\Http\Controllers;

use App\Http\Requests\product\ProductRequest;
use App\Models\Product;
use App\Models\ProductPivotVariation;
use App\Services\ProductService;
use App\Models\ProductVariation;
use App\Models\VariationValues;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{


    public function index()
    {
        try{
            return Product::with('variations','category','variations.variation_items')->where('status', 1)->get();
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

                if(Product::where('route',$request->route)->exists())
                {
                    return response()->json('Route Already Exist');

                }else{

                    #create
                    $product = ProductService::insertProduct($request->all());
                    return $product;
                }


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



    }


    public function disableProducts(){

        try{
            return Product::with('variations','category','variations.variation_items')->where('status', 0)->get();
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }


    public function changeStatus( $id)
    {

        try{
            $product = ProductService::changeStatus($id);
            return $product;
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }

    }


    public function deleteProductVariation($id){

        ProductVariation::where('id',$id)->delete();
        ProductPivotVariation::where('product_variation_id',$id)->delete();
        return response()->json('Variation Deleted Successfully!', 200);

    }






}

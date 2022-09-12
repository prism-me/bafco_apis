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
        $product = Product::where('id',$id)->first();
        if($product['status'] == "0"){
            $update = [
                'status' => 1
            ];
            Product::where('id',$id)->update($update);
        }elseif($product['status'] == "1"){
            $update = [
                'status' => 0
            ];
            Product::where('id',$id)->update($update);
        }
        return response()->json('Product Disabled Successfully!', 200);
    }


    public function deleteProductVariation($id){

        ProductVariation::where('id',$id)->delete();
        ProductPivotVariation::where('product_variation_id',$id)->delete();
        return response()->json('Variation Deleted Successfully!', 200);

    }

    public function cloneVariation($id){

        $variation = Productvariation::where('id',$id)->first();

        $variantCreate = [
            "product_id" => $variation['product_id'],
            "code" => $variation['code'],
            "lc_code" => $variation['lc_code'],
            "cbm" => $variation['cbm'],
            "in_stock" => $variation['in_stock'],
            "upper_price" => $variation['upper_price'],
            "lower_price" => $variation['lower_price'],
            "height" => $variation['height'],
            "depth" => $variation['depth'],
            "width" => $variation['width'],
            "description" => $variation['description'],
            "images" => $variation['images'],
            "lead_img" => isset($variation['lead_img']) ?  $variation['lead_img'] : '',
            "limit" => isset($variation['limit']) ?  $variation['limit'] : ''
        ];

        $variation = Productvariation::firstOrcreate($variantCreate);

        $variationValueId = ProductPivotVariation::where('product_variation_id',$id)->get();
        foreach ($variationValueId as $values) {

            $productVariationId = VariationValues::select('id', 'variation_id')->where('id', $values['variation_value_id'])->first();
            ProductPivotVariation::create([
                "product_id" => $values->product_id,
                "product_variation_id" => $variation->id,
                "variation_id" => $productVariationId->variation_id,
                "variation_value_id" => $values['variation_value_id'],
            ]);

        }
        $productVariation = ProductVariation::where('id',$variation['id'])->with('variation_items')->first();

        return response()->json($productVariation);


    }




}

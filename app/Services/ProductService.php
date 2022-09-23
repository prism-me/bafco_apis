<?php

namespace App\Services;
use App\Models\Product;
use App\Models\ProductPivotVariation;
use App\Models\ProductVariation;
use App\Models\VariationValues;
use DB;

class ProductService
{


    #Add Product
    public  function insertProduct($data)
    {

        try {
            DB::beginTransaction();

                #Create Product
                $product = Product::create([
                    "name" => $data['name'],
                    "featured_image" => $data['featured_image'],
                    "route" => $data['route'],
                    "long_description" => $data['long_description'],
                    "shiping_and_return" => $data['shiping_and_return'],
                    "category_id" => $data['category_id'],
                    "related_categories" => $data['related_categories'],
                    "brand" => $data['brand'],
                    "album" => $data['album'],
                    "download" => $data['download'],
                    "promotional_images" => $data['promotional_images'],
                    "footrest" => $data['footrest'],
                    "headrest" => $data['headrest'],
                    "seo" => $data['seo'],
                    "top_selling" => isset($data['top_selling']) ? $data['top_selling'] : 0,

                ]);


                #Add VariationItems Using Relationship For ProductVariationTable
                $variations = $data['variations'];

                foreach ($variations as $variation) {

                    $item = $variation['variationItems'];

                    $product_variation = $product->variations()->create([
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
                    ]);

                    #Adding Variation Items To the ProductPivotTable
                    foreach ($item as $values) {
                        $productVariationId = VariationValues::select('id', 'variation_id')->where('id', $values)->first();
                        $product_variation->product_variation_name()->create([
                            "product_id" => $product->id,
                            "variation_id" => $productVariationId->variation_id,
                            "variation_value_id" => $values,
                        ]);

                    }

                }

            DB::commit();
            return response()->json('Data has been saved.', 200);

        }

        catch (\Exception $e) {

            DB::rollBack();
            return response(['Product is not added.', 'stack' => $e->getMessage() , 'line' => $e->getLine()], 500);
        }

    }


    #Update Product
    public function updateProduct($data)
    {
        try {
            DB::beginTransaction();
                #Updating Products Against That ID
                $product = Product::where('id', $data['id'])->update([
                    "name" => $data['name'],
                    "featured_image" => $data['featured_image'],
                    "route" => $data['route'],
                    "long_description" => $data['long_description'],
                    "shiping_and_return" => $data['shiping_and_return'],
                    "category_id" => $data['category_id'],
                    "related_categories" => $data['related_categories'],
                    "brand" => $data['brand'],
                    "album" => $data['album'],
                    "download" => $data['download'],
                    "promotional_images" => $data['promotional_images'],
                    "footrest" => $data['footrest'],
                    "headrest" => $data['headrest'],
                    "seo" => $data['seo'],
                    "top_selling" => isset($data['top_selling']) ? $data['top_selling'] : 0,
                ]);




                #Deleting ALl the  Items against that product_id from PivotTable for headrest , footrest and variation values
                ProductPivotVariation::where('product_id', $data['id'])->delete();


                $variations =  $data['variations'];

                #Add Product Variation
                foreach ($variations as $variation) {


                    if(ProductVariation::where('id', @$variation['id'])->exists()) {

                        $product_variation = ProductVariation::where('id', $variation['id'])->first();

                        $product_variation->update([
                            "product_id" => $data['id'],
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
                        ]);



                    }else{

                    $product_variation = ProductVariation::create([
                        "product_id" => $data['id'],
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
                    ]);


                }

                        $item = $variation['variationItems'];

                        #Adding ProductVariationItem to The Pivot Table

                        foreach ($item as $values) {

                            $productVariationId = VariationValues::where('id', $values)->first();
                                ProductPivotVariation::create([
                                    "product_id" => $data['id'],
                                    "product_variation_id" => $product_variation->id,
                                    "variation_id" => $productVariationId->variation_id,
                                    "variation_value_id" => $values
                                ]);

                        }


                }
            DB::commit();
            return response()->json('Data has been saved.', 200);
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json(['Product is not added.', 'stack' => $e], 500);
        }
    }


    public function changeStatus($id){
        try {

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

        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json(['Product is not added.', 'stack' => $e], 500);
        }

    }


     public function addProducts($data){

         $product = Product::create([
             "name" => $data['name'],
             "featured_image" => $data['featured_image'],
             "route" => $data['route'],
             "long_description" => $data['long_description'],
             "shiping_and_return" => $data['shiping_and_return'],
             "category_id" => $data['category_id'],
             "related_categories" => $data['related_categories'],
             "brand" => $data['brand'],
             "album" => $data['album'],
             "download" => $data['download'],
             "promotional_images" => $data['promotional_images'],
             "footrest" => $data['footrest'],
             "headrest" => $data['headrest'],
             "seo" => $data['seo'],
             "top_selling" => isset($data['top_selling']) ? $data['top_selling'] : 0,

         ]);
         return response()->json('Data has been saved.', 200);


     }

     public function updateProducts($data){

         $product = Product::where('id', $data['id'])->update([
             "name" => $data['name'],
             "featured_image" => $data['featured_image'],
             "route" => $data['route'],
             "long_description" => $data['long_description'],
             "shiping_and_return" => $data['shiping_and_return'],
             "category_id" => $data['category_id'],
             "related_categories" => $data['related_categories'],
             "brand" => $data['brand'],
             "album" => $data['album'],
             "download" => $data['download'],
             "promotional_images" => $data['promotional_images'],
             "footrest" => $data['footrest'],
             "headrest" => $data['headrest'],
             "seo" => $data['seo'],
             "top_selling" => isset($data['top_selling']) ? $data['top_selling'] : 0,
         ]);
        return response()->json('Data has been saved.', 200);


    }


    public function addVariation($variation){

        $product_variation = ProductVariation::create([
            "product_id" =>  $variation['product_id'],
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
        ]);
        return response()->json('Data has been saved.', 200);




    }

    public function updateVariation($variation){

        $product_variation = ProductVariation::where('id',$variation['id'])->update([
            "product_id" =>  $variation['product_id'],
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
        ]);
        return response()->json('Data has been saved.', 200);




    }





}

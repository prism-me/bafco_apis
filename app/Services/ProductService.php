<?php

namespace App\Services;
use App\Models\Product;
use App\Models\ProductPivotVariation;
use App\Models\ProductVariation;
use App\Models\VariationValues;
use DB;

class ProductService
{


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


     public function addProduct($data){

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
                "banner_img" => isset($data['banner_img']) ? $data['banner_img'] : 0,
            ]);
        return response()->json('Data has been saved.', 200);
    }

    public function updateProduct($data){

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
             "banner_img" => isset($data['banner_img']) ? $data['banner_img'] : 0,
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


        $item = $variation['variationItems'];
        #Adding Variation Items To the ProductPivotTable
        foreach ($item as $values) {
            $productVariationId = VariationValues::where('id', $values)->first();
                                ProductPivotVariation::create([
                                    "product_id" => $variation['product_id'],
                                    "product_variation_id" => $product_variation->id,
                                    "variation_id" => $productVariationId->variation_id,
                                    "variation_value_id" => $values
                                ]);

        }

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
        ProductPivotVariation::where('product_variation_id', $variation['id'])->delete();
        $item = $variation['variationItems'];
        #Adding Variation Items To the ProductPivotTable
        foreach ($item as $values) {
            $productVariationId = VariationValues::where('id', $values)->first();
                                ProductPivotVariation::create([
                                    "product_id" => $variation['product_id'],
                                    "product_variation_id" => $variation['id'],
                                    "variation_id" => $productVariationId->variation_id,
                                    "variation_value_id" => $values
                                ]);

        }
        return response()->json('Data has been saved.', 200);




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
        return response()->json('Variation Cloned successfully!');

        //$productVariation = ProductVariation::where('id',$variation['id'])->with('variation_items')->first();

        //return $productVariation;


    }




    public function cloneProduct($id){


        $oldProduct = Product::where('id',$id)->first();
        $oldProductVariation = ProductVariation::where('product_id',$id)->get();
        $generateRoute = rand(1,10);
        $newProduct = Product::firstOrCreate([
                "name" => $oldProduct['name'],
                "featured_image" => $oldProduct['featured_image'],
                "route" => $oldProduct['route'] . $generateRoute,
                "long_description" => $oldProduct['long_description'],
                "shiping_and_return" => $oldProduct['shiping_and_return'],
                "category_id" => $oldProduct['category_id'],
                "related_categories" => $oldProduct['related_categories'],
                "brand" => $oldProduct['brand'],
                "album" => $oldProduct['album'],
                "download" => $oldProduct['download'],
                "promotional_images" => $oldProduct['promotional_images'],
                "footrest" => $oldProduct['footrest'],
                "headrest" => $oldProduct['headrest'],
                "seo" => $oldProduct['seo'],
                "top_selling" => isset($oldProduct['top_selling']) ? $oldProduct['top_selling'] : 0,
                "banner_img" => isset($oldProduct['banner_img']) ? $oldProduct['banner_img'] : 0,
        ]);
        $productId = $newProduct['id'];
        //$i = 0; 
        foreach ($oldProductVariation as $variationvalues) {
            $newProductVariation = ProductVariation::firstOrCreate([
                "product_id" =>  $productId,
                "code" => $variationvalues['code'],
                "lc_code" => $variationvalues['lc_code'],
                "cbm" => $variationvalues['cbm'],
                "in_stock" => $variationvalues['in_stock'],
                "upper_price" => $variationvalues['upper_price'],
                "lower_price" => $variationvalues['lower_price'],
                "height" => $variationvalues['height'],
                "depth" => $variationvalues['depth'],
                "width" => $variationvalues['width'],
                "description" => $variationvalues['description'],
                "images" => $variationvalues['images'],
                "lead_img" => isset($variationvalues['lead_img']) ?  $variationvalues['lead_img'] : '',
                "limit" => isset($variationvalues['limit']) ?  $variationvalues['limit'] : ''
            ]);
            
         
            $pivotValue = ProductPivotVariation::where('product_variation_id' , $variationvalues['id'])->get();

            foreach ($pivotValue as $pivotvalues) {
                
                                    ProductPivotVariation::create([
                                        "product_id" => $productId,
                                        "product_variation_id" => $newProductVariation['id'],
                                        "variation_id" => $pivotvalues['variation_id'],
                                        "variation_value_id" =>  $pivotvalues['variation_value_id']
                                    ]);

            }
            //$i ++;
        }

         return response()->json('Product Cloned successfully!');


    }





}

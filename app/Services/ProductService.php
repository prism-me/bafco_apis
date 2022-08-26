<?php

namespace App\Services;
use App\Models\Product;
use App\Models\ProductPivotVariation;
use App\Models\ProductVariation;
use App\Models\VariationValues;

class ProductService
{


    public static function insertProduct($data)
    {

        try {

            \DB::beginTransaction();

            #Create Product
            $product = Product::create([
                "name" => $data['name'],
                "short_description" => $data['short_description'],
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
                    "images" => $variation['images']


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

            \DB::commit();
            return response()->json('Data has been saved.', 200);

        }

        catch (\Exception $e) {

            \DB::rollBack();
            return response(['Product is not added.', 'stack' => $e->getMessage() , 'line' => $e->getLine()], 500);
        }

    }


    #Update Product
    public function updateProduct($data)
    {
        try {

            #Updating Products Against That ID

            $product = Product::where('id', $data['id'])->update([
                "name" => $data['name'],
                "short_description" => $data['short_description'],
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
            return response()->json('Data has been saved.', 200);
        } catch (\Exception $e) {
            // DB::rollBack();
            return response()->json(['Product is not added.', 'stack' => $e], 500);
        }
    }

}

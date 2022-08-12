<?php

namespace App\Services;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductPivotVariation;
use App\Models\VariationValues;
use Illuminate\Support\Facades\DB;

class ProductService
{


    public static function insertProduct($data)
    {

        try {

            //DB::beginTransaction();


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


            $variations = $data['variations'];

            foreach ($variations as $variation) {


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

                ]);
                $item = $variation['variationItems'];


                foreach ($item as $values) {

                    $productVariationId = VariationValues::where('id', $values['variation_value_id'])->first('variation_id');

                    $product_variation->product_variation_name()->create([
                        "product_id" => $product->id,
                        "variation_id" => $productVariationId->variation_id,
                        "variation_value_id" => $values['variation_value_id'],
                    ]);

                }

            }
            //DB::commit();
            return response()->json('Data has been saved.', 200);

        } catch (\Exception $e) {

            // DB::rollBack();
            return response()->json(['Product is not added.', 'stack' => $e], 500);
        }

    }


    #Update Product
    public function updateProduct($data)
    {
        try {
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

            $variations = $data['variations'];

            foreach ($variations as $variation) {

            $product_variation = ProductVariation::where('id', $variation['id'])->update([
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


            $item = $variation['variationItems'];

           foreach ($item as $values) {

                $productVariationId = VariationValues::where('id', $values['variation_value_id'])->first('variation_id');

                ProductPivotVariation::where('id',$values['id'])->update([
                    "variation_id" => $productVariationId->variation_id,
                    "variation_value_id" => $values['variation_value_id'],
                ]);
            }

        }

        } catch (\Exception $e) {

            // DB::rollBack();
            return response()->json(['Product is not added.', 'stack' => $e], 500);
        }

    }

}

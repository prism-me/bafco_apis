<?php 

namespace App\Services;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductPivotVariation;
use Illuminate\Support\Facades\DB;

class ProductService {


    public static function insertProduct($data){

        // dd($data['variations'][0]['variationItems']);
        try{
        
            // DB::beginTransaction();

        $product = Product::create([
            "name" => $data['name'],
            "short_description" => $data['short_description'],
            "featured_image" => $data['featured_image'],
            "route" => $data['route'],
            "long_description" => $data['long_description'],
            "shiping_and_return" => $data['shiping_and_return'],
            "category_id" => $data['category_id'],
            "related_categories" =>$data['related_categories'],
            "brand"=> $data['brand'],
            "album"=> $data['album'],
            "download"=> $data['download'],
            "promotional_images" => $data['promotional_images'],
            "footrest"=>$data['footrest'],
            "headrest"=>$data['headrest'],

        ]);
       
        $variations = $data['variations'];
        // dd($variations);
        foreach ($variations as $variation) {
           

          $product_variation = $product->variations()->create([
                "code"=>  $variation['code'],
                "code"=> $variation['code'],
                "lc_code"=> $variation['lc_code'],
                "cbm"=>$variation['cbm'],
                "in_stock" => $variation['in_stock'],
                "upper_price" => $variation['upper_price'],
                "lower_price" => $variation['lower_price'],
                "height" => $variation['height'],
                "depth" => $variation['depth'],
                "width" => $variation['width'],
                "description" => $variation['description'],
                "images" => $variation['images'],
                
            ]);
           //return explode(",","" , $variation['variationItems']);
           //exit;
          // throw new Exception("Error Processing Request", 1);
          $item = $variation['variationItems'];
          //dd($item);
         
        //   dd($item);
        //   $variationItems = explode("," , $item);
           
            foreach($item as $values){
                
                
                $product_variation->product_variation_name()->create([
                    "variation_id"=> $values,
                    "variation_value_id"=> $values,
                ]);
        
            }
        
    }
        // DB::commit();

    }
    catch (\Exception $e) {
        
        // DB::rollBack();
        return response()->json(['Product is not added.' , 'stack'=>$e],500);
    }

    }

}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Blog;
use App\Models\Team;
use App\Models\Product;
use App\Models\Partner;
use App\Models\Category;
use App\Models\Variation;
use App\Models\ProductVariation;
use App\Models\VariationValues;
use App\Models\ProductPivotVariation;



use App\Models\Testimonial;
use DB;

class FrontController extends Controller
{

    public $blogData;
    public $teamData;
    public $partnerData;
    public $testimonialData;

    public function __construct()
    {
        $this->blogData = Blog::get(['id','title','sub_title','short_description','featured_img','route'])->take(4);
        $this->teamData = Team::get(['id','name','image','designation','gif','route'])->take(8);
        $this->partnerData = Partner::get(['id','name','image','description','logo','route','link']);
        $this->testimonialData = Testimonial::get(['id','designation','img','review']);

    }

    ###Home Section#######

    /* Home Page Data*/
    public function home(){

        $pages = Page::where('identifier','home')->first(['name','content']);
        $blog =  $this->blogData;
        $category = Category::where('parent_id' , '!=' , null)->get()->take( 4);
        $data  = array(
            'category' => $category,
            'pages' => $pages,
            'blogs' => $blog
        );
        return $data;


    }

    /* Product Filter on the basis of Category*/
    public function homeProductCategoryFilter($route)
    {
        if($route == 'all' ){

            $product = Product::with('variations.variation_items.variation_values')->get()->take(4);

            $data = $product;
        }
        else{
            //$productFilter = Category::with('products')->where('route',$route)->get()->take(4);

            $productFilter = Category::with('products.variations.variation_items.variation_values')->where('route',$route)->get()->take(4);
            $data =  $productFilter;

        }

        return response()->json($data);
    }

    #####End Home Section########

    public function about(){

        $about = Page::where('identifier','about')->first(['name','content']);
        $team =  $this->teamData;
        $partner =  $this->partnerData;

        $data  = array(
            'about' => $about,
            'team' => $team,
            'partner' => $partner
        );
        return $data;

    }


    public function contactUs(){

        $contact = Page::where('identifier','contact')->first(['name','content']);
        return $contact;

    }

    public function topManagement(){

        $management = Page::where('identifier','management')->first(['name','content']);
        return $management;

    }

    public function services(){

        $services = Page::where('identifier','services')->first(['name','content']);
        $testimonial = $this->testimonialData;

        $data  = array(
            'services' => $services,
            'testimonial' => $testimonial,
        );
        return $data;

    }

    public function innovations(){

        $innovations = Page::where('identifier','innovations')->first(['name','content']);
        $blog = $this->blogData;

        $data  = array(
            'innovations' => $innovations,
            'blog' => $blog,
        );
        return $data;

    }


    /* Category Page */

    public function category($route){

         $category = Category::where('route' , $route)->with('subcategory_products')->get();
         return response()->json($category);
    }


    /* Products Page*/

    public function frontProducts($route)
    {
        $products = Category::with('products.variations.variation_items.variation_values')->where('route',$route)->paginate(12);
        return response()->json($products);
    }

    public function productDetail($route)
    {
        //\DB::enableQueryLog();
        $productDetails =  Product::where('route', $route)->first();
        $headRest = VariationValues::whereIn('id',$productDetails->headrest )->get();
        $footRest = VariationValues::whereIn('id',$productDetails->footrest )->get();
        $product = [
            'product' => $productDetails,
            'headrest' => $headRest,
            'footrest' => $footRest,

        ];

        $relatedProducts = Product::with('variations','productDetailCategory.parentCategory')->paginate(4);
        $randomProducts = Product::with('variations','productDetailCategory.parentCategory')->inRandomOrder()
        ->limit(4)
        ->paginate(4);

        $dimensions = ProductVariation::where('product_id',$productDetails->id)->get(['product_id', 'id','code','lc_code','height','depth','width' ,'images']);
        $i=0;
        foreach($dimensions as $value){
            $dimensions[$i]['images'] =     $value['images'][0];
            $i++;
        }

         if (isset($productDetails) && !empty($productDetails)) {
            $productVariationIdArr = $productDetails->variations()->pluck('id');

            #First Variation
            $productPivotListingSingle = ProductPivotVariation::whereIn('product_variation_id',$productVariationIdArr)->first();
            $productSingleVariation = Product::getProductDetail($productPivotListingSingle);


            $productPivotListings = ProductPivotVariation::whereIn('product_variation_id',$productVariationIdArr)->get();
            $productAllVariations  = $productPivotListings->transform(function($item){
                $item->product_details = Product::getProductVariation($item);
                return $item;
            })->all();

        }
        return response()->json([
            'single_product_details' => $product,
            'product_single_variation' => $productSingleVariation,
            'product_all_varitaions' => $productAllVariations,
            'related_products' => $relatedProducts,
            'random_purchase' => $relatedProducts,
            'dimensions' => $dimensions
        ]);
    }

    public function productDetailFilter(Request $request){

    }

    public function filterListing($route){

        return VariationValues::with('variationNameValue')->where('route',$route)->get();
//        $category= Category::where('route',$route)->first();
//        $brandValue = Product::distinct()->where('category_id',$category['id'])->get(['brand']);
//        $productValue = Product::where('category_id',$category['id'])->pluck('id');
//
//        //$variation = ProductVariation::whereIn('product_id',$productValue)->with('variation_items.variation_name.variantValues')->get();
//
//        $variation = VariationValues::with('variationNameValue')->whereIn('product_id',$productValue)->get();
//
//        $data  = array(
//            'brand' => $brandValue,
//             'variation' => $variation
//        );
//        return $data;

    }



//    public function test(){
//        \DB::enableQueryLog();
//        return DB::select('CALL GetAllProducts("duncan-fry")');
//         \DB::getQueryLog();
//    }
//
//    public function test1(){
//        \DB::enableQueryLog();
//        return Product::where('route', '=' , 'duncan-fry')->first();
//         \DB::getQueryLog();
//    }





}











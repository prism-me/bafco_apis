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



    /* Products Page*/

    public function frontProducts($route)
    {
        $products = Category::with('products.variations.variation_items.variation_values')->where('route',$route)->paginate(12);
        return response()->json($products);
    }


    public function filterListing($category){
        
        //category
        //product
        //product variation
        //product variation pivot
        //variation items & variation values
        

//hasthroughTest
       return  Product::with(['variations.bridge'])->get();









        \DB::enableQueryLog();
        return $category;
        $brandValue = Product::distinct()->where('category_id',$category->id)->get(['brand']);
        $product = Category::select('id')->with('deep_deep.variantValues')->get();
        $product = $product->collect();

        $product = $product->pluck('deep_deep');
        $processed = [];
        $i=0;
        foreach($product as $pro){
            
            foreach($pro as $p){
                $processed[$i] = $p;
                
                $i++;
            }
        }
        return response()->jsonn([$processed , $brandValue] , 200);


        //     if( && !empty($pro)){
        //         foreach($pro as $p){
        //             $processed[$i] = $p;
        //         }
        //     }else{
        //         // $processed[$i] = $pro;
        //     }
        //     $i++;
        // }
        //return $processed;
        // return $product->unique('name');
        //return \DB::getQueryLog();

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




}










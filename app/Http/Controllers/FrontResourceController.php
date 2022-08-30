<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Blog;
use App\Models\Product;

use App\Models\ProjectCategory;
use Illuminate\Http\Request;

class FrontResourceController extends Controller
{

    public $blogData;


    public function __construct()
    {
        $this->blogData = Blog::get(['id','title','sub_title','short_description','featured_img','route'])->take(3);

    }

    public function index(){

        $project = Project::get(['id','title','sub_title','description','featured_img','additional_img','route','type'])->take(1);
        $blog =  $this->blogData;
        $resource = [
            'project' =>   $project,
            'blog' => $blog
        ];
        return response()->json($resource);
    }



    /* Project Refrences */

    public function allProject($type){

        if($type == "all"){

            $project = Project::with('projectCategory')->get(['id','title','category_id','sub_title','description','additional_img','featured_img','route','type' ,'related_products'])->take(9);

        }else{

            $project = Project::with('projectCategory')->where('categor', $type)->get(['id','title','category_id','sub_title','description','additional_img','featured_img','route','type','related_products'])->take(9);


        }

        return response()->json($project);



    }

    public function projectDetail($id){

        $project = Project::where('id',$id)->first();

        $i = 0;
        foreach($project->category_id as $value){

            $category[$i] = ProjectCategory::where('id',$value)->get();
            $i++;
        }
        $categoryId = Product::whereIn('id',$project->related_products)->pluck('category_id');
        $products = Category::with('parentCategory','products.productvariations.productVariationName.productVariationValues')->where('id',$categoryId)->first(['id','route','name','parent_id']);
        $resources = [
            'project'=> $project,
            'category'=> $category,
            'product' => $products
        ];

        return response()->json($resources);
    }

    /* End Project Refrence */
}

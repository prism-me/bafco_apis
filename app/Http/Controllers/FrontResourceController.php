<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Blog;
use App\Models\Product;
use App\Models\Brochure;
use App\Models\Video;
use App\Models\Plan;
use App\Models\ProjectCategory;
use App\Models\ProjectCategoryPivot;
use Illuminate\Http\Request;

class FrontResourceController extends Controller
{

    public $blogData;


    public function __construct()
    {
        $this->blogData = Blog::get(['id','title','sub_title','short_description','featured_img','route'])->take(3);

    }

    public function index(){

        $project = Project::with('projectCategory')->get(['id','title','sub_title','description','featured_img','additional_img','route'])->take(1);
        $blog =  $this->blogData;
        $videos = Video::where('type', "=", 'resource')->get();
        $plans  = Plan::get()->take(3);
        $resource = [
            'project' =>   $project,
            'blog' => $blog, 
            'videos' => $videos,
            'plans' => $plans,
        ];
        return response()->json($resource);
    }


    /* Project Refrences */

        public function projectCategoryList()
        {
            $category = ProjectCategory::get(['id','name','route']);
            return response()->json($category,200);
        }

        public function allProject($type){

            if($type == "all"){

                $project = Project::with('projectCategory')->get(['id','title','category_id','sub_title','description','additional_img','featured_img','route','related_products'])->take(9);

            }else{

                $category = ProjectCategory::where('route',$type)->first();
                $projectId = ProjectCategoryPivot::where('category_id',$category['id'])->pluck('project_id');
                $project = Project::with('projectCategory')->whereIn('id',$projectId)->get(['id','title','category_id','sub_title','description','additional_img','featured_img','route','related_products'])->take(9);

            }

            return response()->json($project);



        }

        public function projectDetail($id){

            $project = Project::where('id',$id)->with('projectCategory')->first();
            $relatedProducts = Product::with('productCategory.parentCategory','productvariations.productVariationName.productVariationValues')->whereIn('id',$project->related_products)->get();
            $projectDetail = [
                    'project' => $project, 
                    'relatedProducts' => $relatedProducts 
            ];
            return response()->json($projectDetail);
        }

    /* End Project Refrence */


    /* Brochures */

        public function brochureCategoryList(){

            $category =  Category::where('parent_id', '=', NULL)->get(['id','name','route']);
            return response()->json($category);

        }

        public function brochuresFilter($type){

            if($type == "all"){

                $brochure = Brochure::with('broucherCategory')->get(['id','featured_img','title','sub_title']);

            }else{

                $category =  Category::where('route', $type)->first();
                $brochure = Brochure::where('category_id', $category['id'])->with('broucherCategory')->get(['id','featured_img','title','sub_title']);

            }
            return response()->json($brochure);

        }


        public function brochuresDetail($id){

            $brochure = Brochure::where('id',$id)->with('broucherCategory')->first();
            return response()->json($brochure,200);

        }


    /* End Brouchers */



    /* Plans */


        public function planCategoryList()
        {
            $category = ProjectCategory::get(['id','name','route']);
            return response()->json($category,200);
        }

        public function planFilter($type){

            if($type == "all"){

                $plan = Plan::with('planCategory')->paginate(9);

            }else{

                $category = ProjectCategory::where('route',$type)->first();
                $plan = Plan::with('planCategory')->where('category_id',$category->id)->paginate(9);

            }

            return response()->json($plan);
        }


        public function planDetail($id){

            $planDetail = Plan::where('id',$id)->with('planCategory')->first();
            return response()->json($planDetail);
        }



    /* End Plans */
    
}

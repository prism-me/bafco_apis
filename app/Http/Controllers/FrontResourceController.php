<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brochure;
use App\Models\BrochureCategoryPivot;
use App\Models\Category;
use App\Models\Finishes;
use App\Models\FinishesValue;
use App\Models\FinishesValuePivot;
use App\Models\Material;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectCategoryPivot;
use App\Models\Video;
use Illuminate\Http\Request;

class FrontResourceController extends Controller
{

    public $blogData;

    public function __construct()
    {
        $this->blogData = Blog::get(['id','title','sub_title','short_description','featured_img','route'])->take(3);

    }

    public function index(){

        $project = Project::with('projectCategory')->get(['id','title','sub_title','description','featured_img','additional_img','thumbnail_img','route'])->take(1);
        $blog =  $this->blogData;
        $videos = Video::where('type', "=", 'resource')->get()->take(8);
        $plans  = Plan::get()->take(3);
        $pages = Page::where('identifier','=','resources')->first();
        $fabrics = FinishesValue::get('featured_img');
        $resource = [
            'page' => $pages,
            'project' => $project,
            'blog' => $blog,
            'videos' => $videos,
            'plans' => $plans,
            'fabrics' => $fabrics

        ];
        return response()->json($resource);
    }

    /*public function Videos*/

    public function frontVideos(){

        $videos = Video::where('type', "=", 'resource')->get();
        return response()->json($videos);

    }

    /* Project Refrences */

        public function projectCategoryList()
        {
            $category = ProjectCategory::get(['id','name','route']);
            return response()->json($category,200);
        }

        public function allProject($type){

            if($type == "all"){

                $project = Project::with('projectCategory')->get(['id','title','category_id','sub_title','description','additional_img','featured_img','thumbnail_img','route','related_products'])->take(9);

            }else{

                $category = ProjectCategory::where('route',$type)->first();
                $projectId = ProjectCategoryPivot::where('category_id',$category['id'])->pluck('project_id');
                $project = Project::with('projectCategory')->whereIn('id',$projectId)->get(['id','title','category_id','sub_title','description','additional_img','thumbnail_img','featured_img','route','related_products'])->take(9);

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

                $brochure = Brochure::with('broucherCategory')->get(['id','featured_img','title','thumbnail_img','sub_title','files']);

            }else{

                $category = Category::where('route',$type)->first();

                $brochuretId = BrochureCategoryPivot::where('category_id',$category['id'])->pluck('brochure_id');

                $brochure = Brochure::whereIn('id',$brochuretId)->with('broucherCategory')->get(['id','featured_img','title','thumbnail_img','files','sub_title']);

            }
            return response()->json($brochure);

        }

        public function brochuresDetail($type){

            if($type == "all"){

                $brochure = Brochure::with('broucherCategory')->get(['id','featured_img','title','thumbnail_img','sub_title','files']);

            }else{

                $category = ProjectCategory::where('route',$type)->first();
                $brochuretId = BrochureCategoryPivot::where('category_id',$category['id'])->pluck('brochure_id');
                $brochure = Brochure::with('brochureCategory')->whereIn('id',$brochuretId)->get(['id','featured_img','title','thumbnail_img','sub_title','files']);

            }
            return response()->json($brochure);

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

                $plan = Plan::with('planCategory')->get();
                return $plan;

            }else{

                $category = ProjectCategory::where('route',$type)->first();
                $plan = Plan::with('planCategory')->where('category_id', $category->id)->get();

            }

            return response()->json($plan);
        }

        public function planDetail($id){

            $planDetail = Plan::where('id',$id)->with('planCategory')->first();
            return response()->json($planDetail);
        }

    /* End Plans */



    /* Fabrics and Finishes*/




        public function finishesFilterList($type){

            if($type == "Leather"){

                $material = Material::where('name','=', 'Leather')->with('materialValues.values')->first();
                $finishesList = Finishes::with('value','childValue')->where('parent_id', 0)->get();
                $finishes = FinishesValue::where('material_id', $material->id)->where('finishes_id',$finishesList)->pluck('finishes_id');
                $finishesData = Finishes::where('name','Finishes')->with('childValue.value')->get();
                $data = [
                    'finishesList' => $finishesList,
                    'finishesData' => $finishesData,

                ];
                return response()->json($data);

            }else{

                $material = Material::where('name',$type)->with('materialValues.values')->first();

                $finishesList = Finishes::with('value','childValue')->where('parent_id', 0)->get();
//                $finishes = FinishesValue::where('material_id', $material->id)->where('finishes_id',$finishesList)->first();
//                $finishesParent = Finishes::where('id', $finishes['finishes_id'])->with('parent')->first();
//                $ID =  $finishesParent['parent']['id'];
//                $finishesData = Finishes::where('id', $ID)->with('childValue.value')->get();
                $finishesData = Finishes::where('name','Finishes')->with('childValue.value')->get();
                $data = [
                    'finishesList' => $finishesList,
                    'finishesData' => $finishesData,

                ];
                return response()->json($data);



            }

        }


        public function finishesFilterData(Request $request){

            $data = $request->all();
            $materialId = $data['material_id'];
            $finishesId = $data['finishes_id'];
            $finishes = FinishesValue::where('material_id',$materialId)->where('finishes_id',$finishesId)->first();
            $finishesParent = Finishes::where('id', $finishes['finishes_id'])->with('parent')->first();
            $ID =  $finishesParent['parent']['id'];
            $finishesData = Finishes::where('id', $ID)->with('childValue.value')->get();
            $data = [
                'finishesData' => $finishesData,
            ];
            return response()->json($data);

        }

        public function finishesFilterDetail($id){

            $value = FinishesValue::where('finishes_id', $id)->with('values.parent')->first();
            $materialName = Material::where('id',$value['material_id'])->first(['name']);
            $data = [
                'material' => $materialName,
                'detailData' => $value
            ];
            return response()->json($data);



        }






    /**/

}

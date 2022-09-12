<?php

namespace App\Http\Controllers;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{

    public function index(){

        $plan = Plan::with('planCategory')->get();
        return response()->json($plan,200);
    }

    public function store(Request $request){

        $data = $request->all();
        $create = [
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'sub_title' => $data['sub_title'],
            'featured_img' => $data['featured_img'],
            'thumbnail_img' => $data['thumbnail_img'],
            'concept' => $data['concept'],
            'files' => $data['files'],
            'route' => $data['route'],
            'seo' => $data['seo']
        ];
        if(Plan::where('id',$request->id)->exists()){

            $plan = Plan::where('id',$request->id)->update($create);

        }else{
            $plan = Plan::create($create);
        }

        if($plan){

            return response()->json($plan,200);
        }else{

            return response()->json('Something went wrong', 404);
        }


    }


    public function show($id){

        $plan = Plan::where('id',$id)->first();
        if($plan){

            return response()->json($plan,200);
        }else{

            return response()->json('No Data Found', 404);
        }
    }

    public function destroy($id){

        $plan = Plan::where('id',$id)->delete();

        if($plan){

            return response()->json('Data Deleted Successfully', 200);
        }else{

            return response()->json('No Data Found', 404);
        }
    }
}

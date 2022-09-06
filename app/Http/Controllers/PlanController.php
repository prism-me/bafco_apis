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


        if(Plan::where('id',$request->id)->exists()){

            $plan = Plan::where('id',$request->id)->update($request->all());

        }else{
            $plan = Plan::create($request->all());
        }

        if($plan){

            return response()->json($plan,200);
        }else{

            return response()->json('Something went wrong', 404);
        }
        

    }


    public function show($id){

        $plan = Plan::where('id',$id)->with('planCategory')->first();
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

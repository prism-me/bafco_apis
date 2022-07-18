<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    
    public function index()
    {
        try{
            $testimonial = Testimonial::all();
            if($testimonial->isEmpty()){
                 return response()->json([] , 200);
            }
            return response()->json($testimonial, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
    }


    public function store(Request $request)
    {
        try{

            $data =  [
                'name' =>  $request->name,
                'review' =>   $request->review ,
                'designation' =>   $request->designation,
                'img' =>   $request->img,
            ];
    

           if(Testimonial::where('id', $request->id)->exists() ){ 
            //update
                $testimonial = Testimonial::where('id',$request->id)->update($data);
           }else{
            // create
            $testimonial = Testimonial::create($data);
           }
           if($testimonial){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Testimonial Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        } 
    }

    
    public function show(Testimonial $testimonial)
    {
         if(!$testimonial){
            return response()->json('No Record Found.' , 404);
        }
       
        return response()->json($testimonial , 200); 
    }

 
  
    public function destroy(Testimonial $testimonial)
    {
        if($testimonial->delete()){
            return response()->json('Testimonial has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }
}

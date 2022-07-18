<?php

namespace App\Http\Controllers;

use App\Models\Management;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\management\ManagementRequest;

class ManagementController extends Controller
{

    public function index()
    {
        try{
            $management = Management::all();
            if($management->isEmpty()){
                 return response()->json([] , 200);
            }
            return response()->json($management, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
    }


 
    public function store(ManagementRequest $request)
    {
      
        try{
            $data = [
                'type' =>  $request->type ,
                'content' => $request->content
            ];

           if(Management::where('type', $request->type)->exists() OR Management::where('id', $request->id)->exists() ){ 
            //update
                $management = Management::where('id',$request->id)->update($data);
           }else{
            // create
            $management = Management::create($data);
           }
           if($management){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Management Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        } 
    }

 
    public function show(Management $management)
    {
        if(!$management){
            
            return response()->json([] , 200);
        }
       
        return response()->json($management , 200); 
    }


    public function destroy(Management $management)
    {
        if($management->delete()){
            return response()->json('Management has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }
}

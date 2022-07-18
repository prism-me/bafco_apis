<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\faq\FaqRequest;

class FaqController extends Controller
{
    
    public function index()
    {
       try{
            $faq = Faq::all();
            if($faq->isEmpty()){
                  return response()->json([] , 200);
            }
            return response()->json($faq, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
    }

    
 
   public function store(FaqRequest $request)
    {
        try{
            $data = [
                'question'=>    $request->question,
                'answer'=>   $request->answer,
                'type'=>   $request->type,
                'route'=>   $request->route
            ]; 

            if(Faq::where('route', $request->route)->exists() OR Faq::where('id', $request->id)->exists() ){ 
                #update
                    $faq = Faq::where('id',$request->id)->update($data);
            }else{
                #create
                $faq = Faq::create($data);
            }
            if($faq){
                    return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Faq Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }   
        
    }

    
    public function show(Faq $faq)
    {
        if(!$faq){
            return response()->json('No Record Found.' , 404);
        }
        return response()->json($faq , 200);
    }

 

  
    public function destroy(Faq $faq)
    {
     
        if($faq->delete()){
            return response()->json('Faq has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }
}

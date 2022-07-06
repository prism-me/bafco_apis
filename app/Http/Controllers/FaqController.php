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
        $data['question'] =  isset( $request->question ) ? $request->question:'';
        $data['answer'] = isset( $request->answer )? $request->answer:'' ;
        $data['type'] = isset( $request->type )? $request->type:'' ;
        $data['route'] = isset( $request->route )? $request->route:'' ;
    
        try{

           if(Faq::where('route', $request->route)->exists()){ 
            //update
                $faq = Faq::where('route',$request->route)->update($data);
           }else{
            // create
            $faq = Faq::create($data);
           }
           if($faq){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Faq Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch(QueryException $exception){
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine() ], 400);   
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

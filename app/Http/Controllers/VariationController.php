<?php

namespace App\Http\Controllers;

use App\Models\Variation;
use Illuminate\Http\Request;
use App\Http\Requests\variation\VariationRequest;
use Validator;
class VariationController extends Controller
{   
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        try{
            
            $variation = Variation::with('variantValues:id,variation_id,name,type,type_value')->get();

            if($variation->isEmpty()){
                return response()->json(['data', 'No Record Found.'] , 404);
            }
            return response()->json(['data'=> $variation] , 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VariationRequest $request)
    {
        try{

           $request['name'] = strtolower($request->name);

           if(Variation::where('route', $request->route)->exists()){ 
            //update
                $variation = Variation::where('route',$request->route)->update($request->except('id','lang'));
           }else{
            // create
            $variation = Variation::create($request->except('lang'));
           }
           if($variation){
                return  response()->json(['data'=> 'Data has been saved.'] , 200);
            }
        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Variation Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch(QueryException $exception){
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine() ], 400);   
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Variation  $variation
     * @return \Illuminate\Http\Response
     */
    public function show(Variation $variation)
    {
        if(!$variation){
            return response()->json(['data'=> 'No Record Found.'] , 404);
        }
        $variation->variantValues;
        return response()->json(['data'=> $variation] , 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Variation  $variation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Variation $variation)
    {
        if($variation->delete()){
            
            return response()->json(['data'=> 'Variation has been deleted.'] , 200);
        }
        return response()->json(['data'=> 'Server Error.'] , 400);
    }


}

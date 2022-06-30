<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VariationValues;
use Validator;
use DB;
use App\Http\Requests\variant_values\VariationValueRequest;
class VariationValueController extends Controller
{   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            // \DB::enableQueryLog();

            $variation_values = VariationValues::with('variant:id,name')->get();

            // dd(\DB::getQueryLog());

            if($variation_values->isEmpty()){

                return response()->json(['data', 'No Record Found.'] , 404);
            }
            
            return response()->json(['data'=> $variation_values] , 200);
        }
       
        catch (\Exception $eexception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //form request 
    public function store(VariationValueRequest $request)
    {
        $data['variation_id'] = isset( $request->variation_id ) ? $request->variation_id:null;
        $data['name'] = isset( $request->name ) ? $request->name:'';
        $data['route'] = isset( $request->route ) ? $request->route:'';
        $data['type'] = isset( $request->type ) ? $request->type:'';
        $data['type_value'] = isset( $request->type_value ) ? $request->type_value:'';
        try{
            $data['name'] = strtolower($request->name);
            
           if(VariationValues::where('route', $request->route)->exists()){ 
            //update
                $variation_values = VariationValues::where('route',$request->route)->update($data);
           }else{
            // create
                $variation_values = VariationValues::create($data);
           }
           if($variation_values){

                return  response()->json(['data'=> 'Data has been saved.'] , 200);

            }
        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Variation Values Not found.' , 'line' =>$exception->getLine() ], 400);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(VariationValues $variation_value)
    {   
        if(!$variation_value){
            return response()->json(['data'=> 'No Record Found.'] , 404);
        }
        $variation_value->variation_name = $variation_value->variant->name;

        unset($variation_value->variant);
        
        return response()->json(['data'=> $variation_value] , 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VariationValues $variation_value)
    {
        if($variation_value->delete()){

            return response()->json(['data'=> 'Variation value has been deleted.'] , 200);
        }
        return response()->json(['data'=> 'Server Error.'] , 400);
    }
}

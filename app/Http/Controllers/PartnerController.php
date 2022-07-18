<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\partner\PartnerRequest;

class PartnerController extends Controller
{
   
    public function index()
    {
        try{
            $partner = Partner::all();
            if($partner->isEmpty()){
                 return response()->json([] , 200);
            }
            return response()->json($partner, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
    }
 
    
    public function store(PartnerRequest $request)
    {
        try{

            $data = [  
                'name' => $request->name,
                'image' =>  $request->image,
                'route' =>  $request->route,
                'description' =>  $request->description,
                'logo' =>  $request->logo,
                'link' =>  $request->link
            ];

            if(Partner::where('route', $request->route)->exists() OR Partner::where('id', $request->id)->exists()){ 

                #update
                $partner = Partner::where('route',$request->route)->update($data);

            }else{

                #create
                $partner = Partner::create($data);

            }
            if($partner){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Partner Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
    }

   
    public function show(Partner $partner)
    {
        if(!$partner){
            return response()->json('No Record Found.' , 404);
        }
       
        return response()->json($partner , 200); 
    }

    public function destroy(Partner $partner)
    {
       if($partner->delete()){
            return response()->json('Partner has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }
}

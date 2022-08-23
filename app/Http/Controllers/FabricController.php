<?php

namespace App\Http\Controllers;

use App\Models\Fabric;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\fabric\FabricRequest;

class FabricController extends Controller
{
    public function index()
    {
        try{
            $fabric = Fabric::all();
            if($fabric->isEmpty()){
                return response()->json([] , 200);
            }
            return response()->json($fabric, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }


    public function store(FabricRequest $request)
    {

        try{

            $data = [
                'name' =>  $request->name ,
                'type' => $request->type ,
                'color_range' =>  $request->color_range ,
                'featured_img' =>  $request->featured_img ,
                'finish' => $request->finish
            ];


            if( Fabric::where('id', $request->id)->exists()){

                #update
                $fabric = Fabric::where('id', $request->id)->update($data);

            }else{

                #create
                $fabric = Fabric::create($data);
            }
            if($fabric){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Fabric Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }

    public function show($id)
    {
        $fabric = Fabric::where('id',$id)->first();
        if(!$fabric){
            return response()->json('No Record Found.' , 404);
        }

        return response()->json($fabric , 200);
    }


    public function destroy($id)
    {
        $fabric = Fabric::where('id',$id)->first();
        if($fabric->delete()){
            return response()->json('Fabric has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }
}

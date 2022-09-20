<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use DB;

class MaterialController extends Controller
{
    
    public function index()
    {
         try{

            //$material = Material::with('materialValues.values')->get();
            $material = Material::all();
            return response()->json($material, 200);

         }
         catch (\Exception $exception) {
             return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
         }
    }

    public function store(Request $request)
    {

        try{

            $data = [
                    "name" => $request->name,
            ];

            if( Material::where('id', $request->id)->exists()){

                #update
                $material = Material::where('id', $request->id)->update($data);


            }else{

                #create
                $material = Material::create($data);

            }

            return  response()->json('Data has been saved.' , 200);


        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Material Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }

    public function show($id)
    {
        //$material = Material::with('materialValues.values')->where('id',$id)->get();
        $material = Material::where('id',$id)->first();
        return response()->json($material , 200);
    }

    public function destroy($id)
    {
        $material = Material::where('id',$id)->delete();
        if($material){
            return response()->json('Material has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }
}

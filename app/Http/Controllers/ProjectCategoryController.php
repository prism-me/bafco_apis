<?php

namespace App\Http\Controllers;
use App\Models\ProjectCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProjectCategoryController extends Controller
{
    public function index()
    {
        try{
            $projectCategory = ProjectCategory::all();
            if($projectCategory->isEmpty()){
                return response()->json([] , 200);
            }
            return response()->json($projectCategory, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }

    public function store(Request $request)
    {

        try{

            $data = [
                'name' =>  $request->name ,
                'route' => $request->route
            ];


            if(ProjectCategory::where('route', $request->route)->exists()  OR ProjectCategory::where('id', $request->id)->exists()){

                #update
                $projectCategory= ProjectCategory::where('id', $request->id)->update($data);

            }else{

                #create
                $projectCategory = ProjectCategory::create($data);
            }

            if($projectCategory){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Category Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }

    public function show(ProjectCategory $ProjectCategory)
    {


        if(!$ProjectCategory){
            return response()->json('No Record Found.' , 404);
        }

        return response()->json($ProjectCategory , 200);
    }


    public function destroy(ProjectCategory $ProjectCategory)
    {
        if($ProjectCategory->delete()){
            return response()->json('Category has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }


}

<?php

namespace App\Http\Controllers;
use App\Models\Project;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\project\ProjectRequest;

class ProjectController extends Controller
{
    public function index()
    {
        try{
            $project = Project::all();
            if($project->isEmpty()){
                return response()->json([] , 200);
            }
            return response()->json($project, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }

    public function store(ProjectRequest $request)
    {

        try{

            $data = [
                'title' =>  $request->title ,
                'description' =>  $request->description ,
                'type' => $request->type ,
                'featured_img' => $request->featured_img ,
                'additional_img' => $request->additional_img ,
                'route' => $request->route ,
                'seo' => $request->seo
            ];


            if(Project::where('route', $request->route)->exists()  OR Project::where('id', $request->id)->exists()){

                #update
                $project = Project::where('route', $request->route)->update($data);

            }else{

                #create
                $project = Project::create($data);
            }
            if($project){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Project Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }

    public function show(Project $project)
    {
        if(!$project){
            return response()->json('No Record Found.' , 404);
        }

        return response()->json($project , 200);
    }

    public function destroy(Project $project)
    {
        if($project->delete()){
            return response()->json('Project has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }
}

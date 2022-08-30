<?php

namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Product;
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

                $project = Project::get();

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
                'sub_title' =>  $request->sub_title ,
                'category_id' =>  $request->category_id ,
                'description' =>  $request->description ,
                'featured_img' => $request->featured_img ,
                'additional_img' => $request->additional_img ,
                'related_products' => $request->related_products ,
                'route' => $request->route ,
                'files' => $request->files ,
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

        $i = 0;
        foreach($project->category_id as $value){

            $category[$i] = ProjectCategory::where('id',$value)->get();
            $i++;
        }

        $projects = [
            'project' => $project,
            'category' => $category
        ];

        return response()->json($projects , 200);
    }

    public function destroy(Project $project)
    {
        if($project->delete()){
            return response()->json('Project has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }

    public function projectProduct(){

        $product = Product::get(['id','name']);
        return response()->json($product);

    }


}

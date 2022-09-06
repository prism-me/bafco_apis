<?php

namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectCategoryPivot;
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

                $project = Project::with('projectCategory')->get();

                return response()->json($project, 200);
            }
            catch (\Exception $exception) {
                return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
            }
    }

    public function store(ProjectRequest $request)
    {

        try{

            // return $request->files;

            $data = [
                'title' =>  $request->title ,
                'sub_title' =>  $request->sub_title ,
                'category_id' =>  $request->category_id ,
                'description' =>  $request->description ,
                'featured_img' => $request->featured_img ,
                'additional_img' => $request->additional_img ,
                'related_products' => $request->related_products,
                'route' => $request->route,
                'files' => $request->files,
                'seo' => $request->seo
            ];

            return $data;


            if(Project::where('id', $request->id)->exists()){

                #update
                $project = Project::where('id', $request->id)->first();
                $project->update($request->all());
                ProjectCategoryPivot::where('project_id',$project['id'])->delete();

                $i = 0;
                foreach($data['category_id'] as $value){

                    $create = [
                        'project_id' => $project['id'],
                        'category_id' => $value
                    ];


                    $category[$i] = ProjectCategoryPivot::create($create);
                    $i++;
                }

            }else{

                #create
                $project = Project::create($request->all());
                $i = 0;
                foreach($data['category_id'] as $value){

                    $create = [
                        'project_id' => $project['id'],
                        'category_id' => $value
                    ];


                    $category[$i] = ProjectCategoryPivot::create($create);
                    $i++;
                }
            }

                return  response()->json('Data has been saved.' , 200);


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

        $projects = $project->with('projectCategory')->first();

        return response()->json($projects , 200);
    }

    public function destroy(Project $project)
    {
        if($project->delete()){
            return response()->json('Project has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }


    /* List Of Products For CMS Only*/

    public function projectProduct(){

        $product = Product::get(['id','name']);
        return response()->json($product);

    }


}

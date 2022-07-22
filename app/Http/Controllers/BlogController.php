<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\blog\BlogRequest;

class BlogController extends Controller
{
    
    public function index()
    {
        try{
            $blog = Blog::all();
            if($blog->isEmpty()){
                 return response()->json([] , 200);
            }
            return response()->json($blog, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
    }

    public function store(BlogRequest $request)
    {
        try{

            $data = [ 
                    'title' =>  $request->title ,
                    'sub_title' => $request->sub_title ,
                    'description' =>  $request->description ,
                    'short_description' =>  $request->short_description ,
                    'tags' => $request->tags ,
                    'posted_by' => $request->posted_by ,
                    'featured_img' => $request->featured_img ,
                    'banner_img' => $request->banner_img ,
                    'route' => $request->route ,
                    'seo' => $request->seo
            ];
        

                if(Blog::where('route', $request->route)->exists()  OR Blog::where('id', $request->id)->exists()){ 

                    #update
                    $blog = Blog::where('id', $request->id)->update($data);

                }else{

                    #create
                    $blog = Blog::create($data);
                }
                if($blog){
                    return  response()->json('Data has been saved.' , 200);
                }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Blog Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        } 
    }

    public function show(Blog $blog)
    {
        if(!$blog){
            return response()->json('No Record Found.' , 404);
        }
       
        return response()->json($blog , 200);
    }


    public function destroy(Blog $blog)
    {
        if($blog->delete()){
            return response()->json('Blog has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }
}

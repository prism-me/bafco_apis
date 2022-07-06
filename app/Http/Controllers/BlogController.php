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
        $data['name'] =  isset( $request->name ) ? $request->name:'';
        $data['short_description'] = isset( $request->short_description )? $request->short_description:'' ;
        $data['description'] = isset( $request->description )? $request->description: '' ;
        $data['tags'] = isset( $request->tags )? $request->tags:'' ;
        $data['blog_type'] = isset( $request->blog_type )? $request->blog_type:'' ;
        $data['posted_by'] = isset( $request->posted_by )? $request->posted_by:'' ;
        $data['video'] = isset( $request->video )? $request->video:'' ;
        $data['featured_img'] = isset( $request->featured_img )? $request->featured_img:'' ;
        $data['additional_img'] = isset( $request->additional_img )? $request->additional_img:'' ;
        $data['route'] = isset( $request->route )? $request->route:'' ;
    
        try{

           if(Blog::where('route', $request->route)->exists()){ 
            //update
                $blog = Blog::where('route',$request->route)->update($data);
           }else{
            // create
            $blog = Blog::create($data);
           }
           if($blog){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Blog Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch(QueryException $exception){
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine() ], 400);   
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

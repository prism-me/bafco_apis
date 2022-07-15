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
        $data['title'] =  isset( $request->title ) ? $request->title:'';
        $data['sub_title'] = isset( $request->sub_title )? $request->sub_title:'' ;
        $data['description'] = isset( $request->description )? $request->description: '' ;
        $data['short_description'] = isset( $request->short_description )? $request->short_description: '' ;
        $data['tags'] = isset( $request->tags )? $request->tags:'' ;
        $data['posted_by'] = isset( $request->posted_by )? $request->posted_by:'' ;
        $data['featured_img'] = isset( $request->featured_img )? $request->featured_img:'' ;
        $data['banner_img'] = isset( $request->banner_img )? $request->banner_img:'' ;
        $data['route'] = isset( $request->route )? $request->route:'' ;
        $data['seo'] = isset( $request->seo )? $request->seo:'' ;
    
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

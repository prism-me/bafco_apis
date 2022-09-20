<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Services\CartService;
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
            $blog = Blog::get();
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

            $data = $request->all();
            $cart = BlogService::addBlog($data);
            if($cart){
                return response()->json($cart , 200);
            }else{
                return response()->json('Something went wrong!', 404);
            }
        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Cart Value Not found.' , 'line' =>$exception->getLine() ], 400);
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

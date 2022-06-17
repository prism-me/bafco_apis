<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $categories = Category::with('child')->whereNull('parent_id')->get();
            if($categories->isEmpty()){
                return response()->json(['data', 'No Record Found.'] , 404);
            }
            return response()->json(['data', $categories] , 200);
        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Category Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch(QueryException $exception){
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine() ], 400);   
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'featured_image' => 'url',
            'short_description' => 'required|min:11',
            'long_description' =>'required|min:11',
            'route' =>'required',
            'seo' => 'required'
        ]);
        
        if($validator->fails()){ 
            return response()->json(['errors'=>$validator->errors()] , 400);
        }

        try{

           if(Category::where('route', $request->route)->exists()){ 
            //update
                $category = Category::where('route',$request->route)->update($request->all());
           }else{
            // create
            $category = Category::create($request->all());
           }
           if($category){
                return  response()->json(['data'=> 'Data has been saved.'] , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Category Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch(QueryException $exception){
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine() ], 400);   
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }   
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    { 

        if(!$category){
            return response()->json(['data'=> 'No Record Found.'] , 404);
        }
        $category->child =  $category->child;
        return response()->json(['data'=> $category] , 200);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
      abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category->delete()){
            return response()->json(['data'=> 'Category has been deleted.'] , 200);
        }
        return response()->json(['data'=> 'Server Error.'] , 400);
    }
}

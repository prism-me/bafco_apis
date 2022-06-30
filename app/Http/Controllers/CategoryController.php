<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\category\CategoryRequest;

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
            $categories = Category::with('child')->get();
            if($categories->isEmpty()){
                return response()->json('No Record Found.' , 404);
            }
            return response()->json($categories, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $data['name'] =  isset( $request->name ) ? $request->name:'';
        $data['sub_title'] = isset( $request->sub_title )? $request->sub_title:'' ;
        $data['parent_id'] = isset( $request->parent_id )? $request->parent_id: null ;
        $data['featured_image'] = isset( $request->featured_image )? $request->featured_image:'' ;
        $data['banner_image'] = isset( $request->banner_image )? $request->banner_image:'' ;
        $data['description'] = isset( $request->description )? $request->description:'' ;
        $data['seo'] = isset( $request->seo )? $request->seo:'' ;
        $data['route'] = isset( $request->route )? $request->route:'' ;
    
        try{

           if(Category::where('route', $request->route)->exists()){ 
            //update
                $category = Category::where('route',$request->route)->update($data);
           }else{
            // create
            $category = Category::create($data);
           }
           if($category){
                return  response()->json('Data has been saved.' , 200);
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
            return response()->json('No Record Found.' , 404);
        }
        $category->child =  $category->child;
        return response()->json($category , 200);
        
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
            return response()->json('Category has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }


    public function frontpage_category($category){
        
        try{
            $categories = Category::with('subcategory_products')->whereNull('parent_id')->where('route',$category)->get();
            if($categories->isEmpty()){
                return response()->json('No Record Found.' , 404);
            }
            return response()->json($categories , 200);
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



}

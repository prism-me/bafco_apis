<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests\product\ProductRequest;
class ProductController extends Controller
{   
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $products = Product::with('category:name,id')->get();
            if($products->isEmpty()){
                return response()->json('No Record Found.' , 404);
            }
            return response()->json($products, 200);
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
    public function store(ProductRequest $request)
    {
        try{

            if(Product::where('route', $request->route)->exists()){ 
             //update
                 $products = Product::where('route',$request->route)->update($request->all());
            }else{
             // create
             $products = Product::create($request->all());
            }
            if($products){
                 return  response()->json('Data has been saved.' , 200);
             }
 
         }
         catch (ModelNotFoundException  $exception) {
             return response()->json(['ex_message'=>'Product Not found.' , 'line' =>$exception->getLine() ], 400);
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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if(!$product){
            return response()->json('No Record Found.' , 404);
        }
        $product->category =  $product->category;
        return response()->json($product , 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}

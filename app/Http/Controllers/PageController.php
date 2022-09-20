<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\page\PageRequest;


class PageController extends Controller
{

    public function index()
    {
        try{
            $pages = Page::all();
            if($pages->isEmpty()){
                return response()->json([] , 200);
            }
            return response()->json($pages, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }

    public function store(PageRequest  $request)
    {

        try{

            $data = [
               'name' => $request->name,
               'content' => $request->content,
               'route' => $request->route
            ];


            if(Page::where('route', $request->route)->exists() OR Page::where('id', $request->id)->exists() ){

                #update
                $page = Page::where('id',$request->id)->update($data);

            }else{

                #create
                $page = Page::create($data);

            }
            if($page){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Page Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }

    public function show(Page $page)
    {
        if(!$page){

            return response()->json('No Record Found.' , 404);
        }
        return response()->json($page , 200);

    }


    public function destroy(Page $page)
    {
        if($page->delete()){
            return response()->json('Page has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }

}

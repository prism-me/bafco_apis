<?php

namespace App\Http\Controllers;

use App\Models\Brochure;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BrochureController extends Controller
{
    public function index()
    {
        try{

            $brochuer = Brochure::with('broucherCategory')->get();

            return response()->json($brochuer, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }

    public function store(Request $request)
    {

        try{

            $data = [
                    "category_id" => $request->category_id,
                    "title" => $request->title,
                    "sub_title" => $request->sub_title,
                    "featured_img" => $request->featured_img,
                    "short_description" => $request->short_description,
                    "seo" => $request->seo,
                    "thumbnail_img" => $request->thumbnail_img,
                    'files' => $request->files,
        
            ];

            if( Brochure::where('id', $request->id)->exists()){
                
                #update
                $brochuer = Brochure::where('id', $request->id)->update($request->all());


            }else{

                #create
                $brochuer = Brochure::create($request->all());

            }

            return  response()->json('Data has been saved.' , 200);


        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Brochuer Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }

    public function show($id)
    {
        $brochuer = Brochure::where('id',$id)->first();
        return response()->json($brochuer , 200);
    }

    public function destroy($id)
    {
        $brochuer = Brochure::where('id',$id)->delete();
        if($brochuer){
            return response()->json('Brochuer has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }


   


}

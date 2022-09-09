<?php

namespace App\Http\Controllers;

use App\Models\Brochure;
use App\Models\Category;
use App\Models\BrochureCategoryPivot;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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


            $data = $request->all();
            $createBrochure = [
                    "category_id" => $data['category_id'],
                    "title" => $data['title'],
                    "sub_title" => $data['sub_title'],
                    "featured_img" => $data['featured_img'],
                    "short_description" => $data['short_description'],
                    "seo" => $data['seo'],
                    "thumbnail_img" => $data['thumbnail_img'],
                    'files' => $data['files']
            ];

            if( Brochure::where('id', $request->id)->exists()){

                #update
                $brochuer = Brochure::where('id', $request->id)->update($createBrochure);
                BrochureCategoryPivot::where('brochure_id',$request->id)->delete();

                $i = 0;
                foreach($data['category_id'] as $value){

                    $create = [
                        'brochure_id' => $request->id,
                        'category_id' => $value
                    ];


                    $category[$i] = BrochureCategoryPivot::create($create);
                    $i++;
                }

            }else{

                #create
                $brochuer = Brochure::create($createBrochure);
                $i = 0;
                foreach($data['category_id'] as $value){

                    $create = [
                        'brochure_id' => $brochuer['id'],
                        'category_id' => $value
                    ];


                    $category[$i] = BrochureCategoryPivot::create($create);
                    $i++;
                }

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

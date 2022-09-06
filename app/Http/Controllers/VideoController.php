<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Requests\video\VideoRequest;


class VideoController extends Controller
{

    public function index()
    {
        try{
            $video = Video::all();
            if($video->isEmpty()){
                 return response()->json([] , 200);
            }
            return response()->json($video, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
    }

 
    public function store(VideoRequest $request)
    {
       try{

                if(Video::where('id', $request->id)->exists()){ 

                    #update
                    $video = Video::where('id', $request->id)->update($request->all());

                }else{

                    #create
                    $video = Video::create($request->all());
                }
                if($video){
                    return  response()->json('Data has been saved.' , 200);
                }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Video Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        } 
    }

    public function show(Video $video)
    {
        if(!$video){
            return response()->json('No Record Found.' , 404);
        }
       
        return response()->json($video , 200);
    }


    public function destroy(Video $video)
    {
        if($video->delete()){
            return response()->json('Video has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }
}

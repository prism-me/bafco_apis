<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\team\TeamRequest;


class TeamController extends Controller
{
    
    public function index()
    {
        try{
            $team = Team::all();
            if($team->isEmpty()){
                 return response()->json([] , 200);
            }
            return response()->json($team, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
    }

   
    public function store(TeamRequest $request)
    {
        $data['name'] =  isset( $request->name ) ? $request->name:'';
        $data['image'] = isset( $request->image )? $request->image:'' ;
        $data['designation'] = isset( $request->designation )? $request->designation:'' ;
        $data['gif'] = isset( $request->gif )? $request->gif:'' ;
        $data['route'] = isset( $request->route )? $request->route:'' ;
    
        try{

           if(Team::where('route', $request->route)->exists()){ 
            //update
                $team = Team::where('route',$request->route)->update($data);
           }else{
            // create
            $team = Team::create($data);
           }
           if($team){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Team Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch(QueryException $exception){
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine() ], 400);   
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        } 
    }

    public function show(Team $team)
    {
        if(!$team){
            return response()->json('No Record Found.' , 404);
        }
       
        return response()->json($team , 200); 
    }


    public function destroy(Team $team)
    {
        if($team->delete()){
            return response()->json('Team has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
      
    }
}

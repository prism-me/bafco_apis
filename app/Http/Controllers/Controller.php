<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

}



// try{
// Logic here
// }
// catch (ModelNotFoundException  $exception) {
//    return response()->json(['ex_message'=>'Category Not found.' , 'line' =>$exception->getLine() ], 400);
// }
// catch(QueryException $exception){
//    return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine() ], 400);
// }
// catch (\Exception $exception) {
//    return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine() ], 400);
// }
// catch (\Error $exception) {
//    return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
// }

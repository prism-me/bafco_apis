<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class ContactUsController extends Controller
{
    
    public function index()
    {
        try{
            $contact = ContactUs::all();
            if($contact->isEmpty()){
                    return response()->json([] , 200);
            }
            return response()->json($contact, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
    }


 
    public function store(Request $request)
    {
        $data['name'] =  isset( $request->name ) ? $request->name:'';
        $data['email'] = isset( $request->email )? $request->email:'' ;
        $data['phone'] = isset( $request->phone )? $request->phone:'' ;
        $data['message'] = isset( $request->message )? $request->message:'' ;
        $data['subject'] = isset( $request->subject )? $request->subject:'' ;
        try{

            $ContactUs = ContactUs::create($data);
           
           if($ContactUs){
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


    public function show(ContactUs $contactUs)
    {
        if(!$contactUs){
            return response()->json('No Record Found.' , 404);
        }
       
        return response()->json($contactUs , 200); 
    }



    public function destroy(ContactUs $contactUs)
    {
        if($contactUs->delete()){
            return response()->json('Data has been deleted.' , 200);
        }
        return response()->json('Server Error.' , 400);
    }
}

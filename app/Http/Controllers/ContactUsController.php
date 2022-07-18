<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\contact\ContactUsRequest;


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


 
    public function store(ContactUsRequest $request)
    {
        try{
       
            $data = [
                'name'  => $request->name ,
                'email' => $request->email,
                'phone' => $request->phone, 
                'message' => $request->message,
                'subject' => $request->subject
            ]; 

            $ContactUs = ContactUs::create($data);
           
           if($ContactUs){
                return  response()->json('Query Submitted Successfully.' , 200);
            }

        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Contact Not found.' , 'line' =>$exception->getLine() ], 400);
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

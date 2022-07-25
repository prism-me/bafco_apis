<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class AddressController extends Controller
{
   
    public function index()
    {

        try{
            $user =  auth()->user()->id;
            $address = Address::where('user_id',$user)->get();
            if($address->isEmpty()){
                 return response()->json([] , 200);
            }
            return response()->json($address, 200);
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
        
    }

    public function store(Request $request)
    {
        try{

            $address = AddressService::addAddress($request->all());
            
            return $address;
            if($address){

                 return  response()->json('Data has been saved.' , 200);
            } 
        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Address Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        } 
    }
   
   
    public function destroy(Address $address)
    {
      
        $address = Address::where('id',$address['id'])->delete();
        if($address){
            return response()->json('Address has been deleted.' , 200);
        }

    }


    public function setDefault($id){

        try{
            
            $address = AddressService::setDefaultAddress($request->all());
            
            return $address;
            if($address){

                 return  response()->json('Data has been saved.' , 200);
            } 
            
        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'Address Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }


    }
}
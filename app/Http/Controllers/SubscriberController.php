<?php

namespace App\Http\Controllers;

use App\Mail\ClientSubscriberMail;
use App\Mail\UserSubscriberMail;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Mail;

class SubscriberController extends Controller
{
    public function index()
    {
        $subsciber = Subscriber::get();
        return response()->json($subsciber);
    }



    public function store(Request $request)
    {
        $data = $request->all();
        $create = Subscriber::create($data);
        $clientEmail = "devteam5@prism-me.com";
        $userMail = $data['email'];
        Mail::to($clientEmail)->send(new ClientSubscriberMail($data));
        Mail::to($userMail)->send(new UserSubscriberMail($data));


        if($create){

            return response()->json('Data saved Successfully');

        }else{
            return response()->json('Something went wrong');


        }

    }


    public function destroy($id)
    {
        $subscriber = Subscriber::where('id',$id)->delete();

        if($subscriber){
            return response()->json('Data deleted Succsessfully');
        }else{
            return response()->json('Something went wrong');

        }

    }
}

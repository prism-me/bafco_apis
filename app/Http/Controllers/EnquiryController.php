<?php

namespace App\Http\Controllers;

use App\Mail\ClientEnquiryMail;
use App\Mail\UserEnquiryMail;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Mail;

class EnquiryController extends Controller
{

    public function index()
    {
        $enquiry = Enquiry::get();
        return response()->json($enquiry);
    }



    public function store(Request $request)
    {
        $data = $request->all();
        $create = Enquiry::create($data);
        $clientEmail = "Hello@bafco.com";
        $userMail = $data['email'];
        Mail::to($clientEmail)->send(new ClientEnquiryMail($data));
        Mail::to($userMail)->send(new UserEnquiryMail($data));


        if($create){

            return response()->json('Data saved Successfully');

        }else{
            return response()->json('Something went wrong');


        }

    }

    public function show($id)
    {
        $enquiry = Enquiry::where('id',$id)->first();
        return response()->json($enquiry);
    }


    public function destroy($id)
    {
        $enquiry = Enquiry::where('id',$id)->delete();

        if($enquiry){
            return response()->json('Data deleted Succsessfully');
        }else{
            return response()->json('Something went wrong');

        }

    }
}

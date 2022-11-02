<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use App\Models\Upload;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\contact\ContactUsRequest;
use BunnyCDN\Storage\BunnyCDNStorage;
use App\Http\Requests\upload\UploadRequest;

use App\Mail\ClientContactUsMail;
use App\Mail\UserContactUsMail;
use Mail;

class ContactUsController extends Controller
{
    public $bunnyCDNStorage ;
    public $storageZone = 'bafco';
    public $directory = '/bafco/images';
    public $base_URL = 'https://bafco.b-cdn.net/images/';
    public $access_key = '650cdf14-326b-44a7-9b1ef138dd2e-2583-4f15';

    //public $bunny ;

    public function __construct()
    {

        $this->bunnyCDNStorage = new BunnyCDNStorage($this->storageZone, $this->access_key, "sg");
    }

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
        $formData = $request->all();
        $input = json_decode($formData['contactData'][0]);
        $data = $input;
        
        if($file = $request->file('image')){
            
            $without_ext_name=preg_replace('/\..+$/', '', $file->getClientOriginalName());

            $name = $without_ext_name .'-'. time().rand(1,100).'.'.$file->extension();

            $files['name'] = $name;
            $files['url'] = 'https://bafco.b-cdn.net/forms/'. $name ;
            $files['alt_tag'] = time().rand(1,100);
            $files['type'] = 'img';
            if($this->bunnyCDNStorage->uploadFile($file->getPathName() , $this->storageZone."/forms/{$name}")){
                $isUpdated = Upload::create(['avatar'=> $name,'url' =>$files['url'],'alt_tag'=>$files['alt_tag'],'type'=>$files['type']]);
               
               $create= array(
                    'name' => $input->name,
                    'email' => $input->email,
                    'phone' =>$input->phone,
                    'message' => $input->message,
                    'subject' => $input->subject,
                    'form_type' => isset($input->form_type ) ? $input->form_type : 0 ,
                    'attachment'  => $files['url'],


                ); 

                $data->attachment= $files['url'];
                $save = ContactUs::create($create);
                $clientEmail = "Hello@bafco.com";
                $userMail = $input->email;
                Mail::to($clientEmail)->send(new ClientContactUsMail($data));
                Mail::to($userMail)->send(new UserContactUsMail($data));
                return response()->json('Your Query has been Submitted');

            }  

        }else{
             
                $create= array(
                    'name' => $input->name,
                    'email' => $input->email,
                    'phone' =>$input->phone,
                    'message' => $input->message,
                    'subject' => $input->subject,
                    'form_type' => isset($input->form_type ) ? $input->form_type : 0 ,

                ); 
                $save = ContactUs::create($create);
                $clientEmail = "Hello@bafco.com";
                $userMail = $input->email;
                Mail::to($clientEmail)->send(new ClientEnquiryMail($data));
                Mail::to($userMail)->send(new UserEnquiryMail($data));
                return response()->json('Your Query has been Submitted');

        } 

    }


    public function show(ContactUs $contactUs)
    {
        if(!$contactUs){
            return response()->json('No Record Found.' , 404);
        }

        return response()->json($contactUs , 200);
    }



    public function destroy($id)
    {

        ContactUs::where('id',$id)->delete();
        return response()->json('Data has been deleted.' , 200);
       
    }
}

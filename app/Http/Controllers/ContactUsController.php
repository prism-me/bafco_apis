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



    public function store(ContactUsRequest $request)
    {
        $formData = $request->all();
        $input = json_decode($formData['attachment'][0]);

        $file = $request->file('image');

        $without_ext_name=preg_replace('/\..+$/', '', $file->getClientOriginalName());

        $name = $without_ext_name .'-'. time().rand(1,100).'.'.$file->extension();

        $files['name'] = $name;
        $files['url'] = 'https://makeen.b-cdn.net/forms/'. $name ;
        $files['alt_tag'] = time().rand(1,100);
        $files['type'] = 'img';
        if($this->bunnyCDNStorage->uploadFile($file->getPathName() , $this->storageZone."/forms/{$name}")){
            $isUpdated = Upload::create(['avatar'=> $name,'url' =>$files['url'],'alt_tag'=>$files['alt_tag'],'type'=>$files['type']]);
            $create= array(
                'name' => $input->name,
                'email' => $input->email,
                'phone' =>$input->phone ,
                'message' => $input->message,
                'subject' => $input->subject,
                'form_type' => $input->form_type ,
                'attachment' => $files['url']

            );

            $save = ContactUs::create($create);
            if($save){
                echo json_encode(['status'=>1,'message'=>'Your Investment has been added']);
            }else{
                echo json_encode(['status'=>0,'message'=>'Server Error while']);
            }

        }
//        try{
//
//            $data = [
//                'name'  => $request->name ,
//                'email' => $request->email,
//                'phone' => $request->phone,
//                'message' => $request->message,
//                'subject' => $request->subject
//            ];
//
//            $ContactUs = ContactUs::create($data);
//
//           if($ContactUs){
//                return  response()->json('Query Submitted Successfully.' , 200);
//            }
//
//        }
//        catch (ModelNotFoundException  $exception) {
//            return response()->json(['ex_message'=>'Contact Not found.' , 'line' =>$exception->getLine() ], 400);
//        }
//        catch (\Error $exception) {
//            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
//        }
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

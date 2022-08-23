<?php

namespace App\Services;
use App\Models\Enquiry;

class AddressService {

    public function formSubmit($data){
        if(!empty($data['attachment'])){

            $media = $data['attachment'];
            $media = UploadController::upload_media($media);
            $mediaUpload = $media['url'];
        }
        $create = [
            'email' => $data['email'],
            'message' => $data['message'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'subject' => $data['subject'],
            'attachment' => $mediaUpload,
        ];

        $save = Enquiry::create($create);
        if($save){
            echo json_encode(['status'=>1,'message'=>'Your Investment has been added']);
        }else{
            echo json_encode(['status'=>0,'message'=>'Server Error while']);
        }
                

    }


}
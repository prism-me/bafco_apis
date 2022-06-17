<?php

namespace App\Http\Controllers\Api;

use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use BunnyCDN\Storage\BunnyCDNStorage;
use App\Http\Controllers\API\BaseController as BaseController;


class UploadController extends BaseController
{
    public $bunnyCDNStorage ;
    public $storageZone = 'bafco';
    public $directory = '/bafco/images';
    public $access_key = '650cdf14-326b-44a7-9b1ef138dd2e-2583-4f15';
                          
    //public $bunny ;

    public function __construct()
    {

       $this->bunnyCDNStorage = new BunnyCDNStorage($this->storageZone, $this->access_key, "sg");
    }

    public function upload_media(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'images' => 'required',
        ]);

        if( ! $validator->fails()){

        $data = $request['data'];


        $images = $request->file('images');

        $files = [];

        if($data && $images)
         {

             $i =0 ;
            foreach($data as $d)
            {
                $d = json_decode($d , true);

                $type = $d['is360'] === 'false' ?  'image' : '3d';
                $folder = $type === 'image' ? 'images' : '360';
                $without_ext_name= $this->slugify(preg_replace('/\..+$/', '', $images[$i]->getClientOriginalName()));

                $name = $without_ext_name .'-'. time().rand(1,100).'.'.$images[$i]->extension();
                $files[$i]['avatar'] = $name;
                $files[$i]['url'] = 'bafco.b-cdn.net/images/'. $name ;
                $files[$i]['alt_tag'] = $d['alt_text'];
                $files[$i]['type'] = $type;

                if($this->bunnyCDNStorage->uploadFile($images[$i]->getPathName() , $this->storageZone."/images/{$name}")){

                $isUploaded = Upload::create(['avatar'=> $name,'url' =>$files[$i]['url'] ,'alt_tag' => $files[$i]['alt_tag'] ,'type' =>'asdf']);

                echo json_encode(['message' =>'media has uploaded.' , 'status' =>200]);

                }else{

                   return $errors = ['message'=>'server issue','status'=>404 ,'image_name'=>$file->getClientOrignalName()];
                }

                $i ++;
            }
         }else{
              echo json_encode(['message' =>'files are not uploaded' , 'status' =>404]);

         }

        }
        else{

            echo json_encode(['message'=>$validator->errors(),'status'=>404]);

        }

    }


    public function get_all_images(){


        $data = Upload::orderByDesc('id')->get();

        echo json_encode(['data'=>$data ,'status'=>200]);
     }

    public function update_image($file , $id){

        $existing_data = Upload::select('name')->where('_id',$id)->first();
        $existing_name = $existing_data->name;

        $without_ext_name= $this->slugify(preg_replace('/\..+$/', '', $file->getClientOriginalName()));

        $name = $without_ext_name .'-'. time().rand(1,100).'.'.$file->extension();
        $files[$i]['name'] = $name;
        $files[$i]['url'] = 'https://mic-robotics.b-cdn.net/images/'. $name ;
        $files[$i]['alt_tag'] = time().rand(1,100);

        if($this->bunnyCDNStorage->uploadFile($file->getPathName() , $this->storageZone."/images/{$name}")){

        $isUpdated = Upload::where('_id' ,$id)->update(['name'=> $name,'url' =>$files[$i]['url']]);

        if(! $this->bunnyCDNStorage->deleteObject( '/mic-robotics/images/'.$existing_name))
        {
            echo json_encode(['message' => 'Bucket error' , 'status' => 404]);
        }

        }else{

            return $errors = ['message'=>'server issue','status'=>404 ,'image_name'=>$file->getClientOrignalName()];
        }


    }

    public function delete_images($id){

    $data = Upload::select('avatar')->where('id',$id)->first();

        if(Upload::where('id',$id)->delete()){


            if(! $this->bunnyCDNStorage->deleteObject( '/mic-robotics/images/'.$data->avatar))
            {
                echo json_encode(['message' => 'Bucket error' , 'status' => 404]);
            }

            echo json_encode(['message'=>'Data has been deleted','status'=>200]);

        }else{
            echo json_encode(['message'=>'Data has not been deleted' ,'status'=>404]);
        }

    }

    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
       // $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            echo 'n-a';
        }

        return $text;
    }

}

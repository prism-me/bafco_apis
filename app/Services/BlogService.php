<?php

namespace App\Services;
use App\Models\Blog;

class BlogService {

    static function addBlog($data){

        $data = [
            'title' =>  $data['title'] ,
            'sub_title' => $data['sub_title'] ,
            'description' =>  $data['description'] ,
            'short_description' =>  $data['short_description'] ,
            'tags' => $data['tags'] ,
            'posted_by' => $data['posted_by'] ,
            'featured_img' => $data['featured_img'] ,
            'banner_img' => $data['banner_img'] ,
            'route' => $data['route'] ,
            'seo' => $data['seo']
        ];


        if(Blog::where('route', $data['route'])->exists()  OR Blog::where('id', $data['id'])->exists()){

            #update
            $blog = Blog::where('id', $data['id'])->update($data);

        }else{

            #create
            $blog = Blog::create($data);
        }
        if($blog){
            return  response()->json('Data has been saved.' , 200);
        }
    }
}

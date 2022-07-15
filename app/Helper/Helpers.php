<?php
  namespace App\Helper;
  use App\Models\Blog;


  class Helpers
  {
    #Get Category Data From Category Model
    static function get_blogData()
    {
        $blogData = Blog::get(['id','title','sub_title','short_description','route'])->take(4);
        return $blogData;
    }
  }

  ?>
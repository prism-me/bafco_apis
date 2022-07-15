<?php
  namespace App\Helper;
  use App\Models\Blog;
  use App\Models\Team;
  use App\Models\Partner;
  use App\Models\Testimonial;


  class Helpers
  {
      #Get Category Data From Category Model
      static function get_blogData()
      {
          $blogData = Blog::get(['id','title','sub_title','short_description','route'])->take(4);
          return $blogData;
      }



      static function get_teamData(){

          $teamData = Team::get(['id','name','image','designation','route'])->take(8);
          return $teamData;

      }


      static function get_partnerData(){

          $partnerData = Partner::get(['id','name','image','description','route','link']);
          return $partnerData;

      }

      static function get_testimonialData(){

          $testimonialData = Testimonial::get(['id','designation','img','review']);
          return $testimonialData;

      }
  }

  ?>
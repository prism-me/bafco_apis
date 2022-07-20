<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Blog;
use App\Models\Team;
use App\Models\Partner;
use App\Models\Testimonial;

class FrontController extends Controller
{

    public $blogData;
    public $teamData;
    public $partnerData;
    public $testimonialData;

    public function __construct()
    {
        $this->blogData = Blog::get(['id','title','sub_title','short_description','featured_img','route'])->take(4);
        $this->teamData = Team::get(['id','name','image','designation','gif','route'])->take(8);
        $this->partnerData = Partner::get(['id','name','image','description','logo','route','link']);
        $this->testimonialData = Testimonial::get(['id','designation','img','review']);
       
    }

    public function home(){

        $pages = Page::where('identifier','home')->first(['name','content']);
        $blog =  $this->blogData;
        $data  = array(
            'pages' => $pages,
            'blogs' => $blog
        );
        return $data;


    }

    public function about(){

        $about = Page::where('identifier','about')->first(['name','content']);
        $team =  $this->teamData;
        $partner =  $this->partnerData;

        $data  = array(
            'about' => $about,
            'team' => $team,
            'partner' => $partner
        );
        return $data;

    }


    public function contactUs(){

        $contact = Page::where('identifier','contact')->first(['name','content']);
        return $contact;

    }

    public function topManagement(){

        $management = Page::where('identifier','management')->first(['name','content']);
        return $management;

    }

    public function services(){

        $services = Page::where('identifier','services')->first(['name','content']);
        $testimonial = $this->testimonialData;

        $data  = array(
            'services' => $services,
            'testimonial' => $testimonial,
        );
        return $data;

    }

    public function innovations(){

        $innovations = Page::where('identifier','innovations')->first(['name','content']);
        $blog = $this->blogData;

        $data  = array(
            'innovations' => $innovations,
            'blog' => $blog,
        );
        return $data;

    }

   
}

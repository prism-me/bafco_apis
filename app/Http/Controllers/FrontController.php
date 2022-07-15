<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Helper\Helpers;

class FrontController extends Controller
{

    public function home(){

        $pages = Page::where('identifier','home')->first(['name','content']);
        $blog = Helpers::get_blogData();
        $data  = array(
            'pages' => $pages,
            'blogs' => $blog
        );
        return $data;


    }

    public function about(){

        $about = Page::where('identifier','about')->first(['name','content']);
        $team = Helpers::get_teamData();
        $partner = Helpers::get_partnerData();

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
        $testimonial = Helpers::get_testimonialData();

        $data  = array(
            'services' => $services,
            'testimonial' => $testimonial,
        );
        return $data;

    }

    public function innovations(){

        $innovations = Page::where('identifier','innovations')->first(['name','content']);
        $blog = Helpers::get_blogData();

        $data  = array(
            'innovations' => $innovations,
            'blog' => $blog,
        );
        return $data;

    }
}

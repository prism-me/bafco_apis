<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Helper\Helpers;

class FrontController extends Controller
{

    public function index(){

        $pages = Page::where('route','home-page')->first();
        $data = Helpers::get_blogData();
        return $data . $pages;

    }
}

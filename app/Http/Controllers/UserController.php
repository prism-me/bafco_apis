<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Session;

class UserController extends Controller
{
    public function index(User $user){

        // //$users = User::find(1222222);
        // return $user;

        Session::put('user', 'en/ar');
        Session::save();
    }
}

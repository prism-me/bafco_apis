<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{


    public function allUsers(){

        $users = User::where('user_type' ,'!=', 'admin')->get();
        return response()->json($users);

    }
   

}

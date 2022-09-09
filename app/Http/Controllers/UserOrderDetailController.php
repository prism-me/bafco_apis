<?php

namespace App\Http\Controllers;
use App\Models\Order;

use Illuminate\Http\Request;

class UserOrderDetailController extends Controller
{

    public function userOrderDetail($id){

        $order = Order::where('user_id',$id)->with('order_details','orderAddress')->get();
        if($order){

            return response()->json($order,200);

        }else{

            return response()->json('No Data Found');

        }
    }
}

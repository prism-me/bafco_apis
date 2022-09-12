<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;


class DashboardController extends Controller
{


    public function allUsers(){

        $users = User::where('user_type' ,'!=', 'admin')->with('addressDetail', function($query){
            $query->where('default', '=', 1);
        })->get();
        return response()->json($users);

    }

    public function allOrder(){

        $order = Order::with('order_details','userDetail')->get();
        return response()->json($order);

    }

    public function orderDetail($id){

        $order = Order::where('id',$id)->with(['order_details'=> function ($query) {
            $query->with('productDetail')->with('variationDetail');
        }])
            ->with('orderAddress','userDetail')->first();
        return response()->json($order);


    }

    public function confirmOrder(Request $request){

        $update['status'] = 'ORDERCONFIRMED';
        $updateOrder = Order::where('id',$request->id)->update($update);
        return response()->json('Order Confirmed Successfully');

    }

    public function cancelOrder(Request $request){

        $update['status'] = 'ORDERCANCELLED';
        $updateOrder = Order::where('id',$request->id)->update($update);
        return response()->json('Order Cancelled Successfully');

    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentHistory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class DashboardController extends Controller
{

    private $confirm = "ORDERCONFIRMED";
    private $cancel =    "ORDERCANCELLED";
    private $dispatch = "ORDERDISPATCHED";
    private $deliver = "ORDERDELIVERED";

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

    public function changeOrderStatus(Request $request){

        $status = Order::where('id',$request->id)->first();
        $update['status'] = $status['status'];
        if($request->status ==  "confirm")
        {
            $update['status'] = $this->confirm;

        }elseif($request->status ==  "cancel")
        {
            $update['status'] = $this->cancel;

        }elseif($request->status ==  "dispatch")
        {
            $update['status'] = $this->dispatch;

        }elseif($request->status ==  "deliver")
        {
            $update['status'] = $this->deliver;

        }else{

           return response()->json('Something went Wrong');

        }

        $updateOrder = Order::where('id',$request->id)->update($update);
        return response()->json('Status Updated Successfully');

    }



    /*Product Report*/

    public function productReportList(){

        $productId = OrderDetail::get();
        $Id =  $productId->groupBy('product_id');
        $count = $Id->transform(function ($item){
            return $item->count();
        })->all();

        $i = 0;
        $collection = [];
        foreach($count as $key => $value)
        {

            $collection['productData'][$i] = OrderDetail::where('product_id',$key)->with('productDetail','variationDetail')->first();

            $i++;
        }

        return $collection;

    }

    public function productReportDetail($id){

        $product['detail'] = OrderDetail::where('product_id' , $id)->with(['order','productDetail','variationDetail'])->get();
        $i = 0;

        foreach($product as $value){

            $product['total_purchase'][$i] = $value->sum('qty');
            $product['total_amount'][$i] = $value->sum('total');
            $i ++;

        }
        $product['productDetail'] = Product::where('id',$id)->first('name');
        return  $product;

    }

    /**/



    /* Payment Transaction History */

        public function transaction(){

            $transaction = PaymentHistory::with(['userDetail','orderDetail.transactionAddress'])->get();
            return response()->json($transaction);
        }

        public function transactionFilter(Request $request){

            $results = PaymentHistory::when(!empty($request->startDate), function($q){
                $start = Carbon::createFromFormat('Y-m-d', request('startDate'))->startOfDay();
                $q->where('created_at','>=', $start);

            })
                ->when(!empty($request->endDate), function($q){
                    $end = Carbon::createFromFormat('Y-m-d', request('endDate'))->endOfDay();
                    $q->where('created_at', '<=', $end);
                })
                ->when(!empty($request->status), function($q){
                    $q->where('status', request('status'));
                })
                ->when(count($request->all()) === 0, function($q){
                    return ['data'=>'No record found.','status'=>404];
                })->with(['userDetail','orderDetail.transactionAddress'])->get();

            if($results->count() > 0){
                return response($results , 200);
            }else{
                return ['data'=>'No record found.','status'=>404];
            }
        }

    /* End Payment Transaction History */


    /* Sales */

        public function salesList(){
            $sales = Order::where('status',"ORDERDELIVERED")->with('userDetail')->get();
            return response()->json($sales);
        }

        public function salesCount(){
            $total = Order::where('status',"ORDERDELIVERED")->pluck('total');
            $discount = Order::where('status',"ORDERDELIVERED")->pluck('discount');
            $totalSales = $total->sum();
            $totalDiscount = $discount->sum();
            $count = [
                'totalSales' => $totalSales,
                'discount' => $totalDiscount
            ];
            return $count;


        }
    /* End Sales*/

    /* Main Dashboard Page */

        public function dashboardDetails(Request $request){

            $start = Carbon::createFromFormat('Y-m-d', request('startDate'))->endOfDay();
            $end = Carbon::createFromFormat('Y-m-d', request('endDate'))->endOfDay();
            $totalOrder = Order::where('created_at','>=', $start)->where('created_at' , '<=',$end)->get()->count();
            $totalSales = Order::where('created_at','>=', $start)->where('created_at' , '<=',$end)->get()->sum('total');
            $totalUsers = User::where('created_at','>=', $start)->where('created_at' , '<=',$end)->get()->count();
            $totalProducts = Product::where('created_at','>=', $start)->where('created_at' , '<=',$end)->get()->count();
            $data = [
              'totalOrder' => $totalOrder,
              'totalSales' => $totalSales,
              'totalUsers' => $totalUsers,
              'totalProducts' => $totalProducts
            ];
            return response()->json($data);


        }
    /* End Main Dashboard Page */

}

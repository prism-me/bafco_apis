<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentHistory;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Events\OrderCancelMail;
use App\Events\ClientOrderCancelMail;
use App\Events\OrderDeliverMail;
use App\Events\ClientOrderDeliverMail;



class DashboardController extends Controller
{

    private $confirm = "ORDERCONFIRMED";
    private $cancel =    "ORDERCANCELLED";
    private $dispatch = "ORDERDISPATCHED";
    private $deliver = "ORDERDELIVERED";

        public function allUsers(){

            $users = User::where('user_type' ,'!=', 'admin')->with('addressDetail')->get();
            return response()->json($users);

        }

        public function allOrder(){

            $order = Order::with('userDetail')->orderBy('id', 'desc')->get();
            return response()->json($order);

        }

        public function orderDetail($id){

            $order = Order::where('id',$id)->with(['order_details'=> function ($query) {
                $query->with('productDetail')->with('variationDetail.variation_items.variation_name')->with('variationDetail.variation_items.variation_values');
            }])
                ->with('orderAddress','userDetail')->first();
            return response()->json($order);


        }
        
        public function orderFilter(Request $request){
        
            $results = Order::when(!empty($request->startDate), function($q){
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
            })->get();
            
            if($results->count() > 0){
                return response($results , 200);
            }else{
                return ['data'=>'No record found.','status'=>404];
            }
        }

        public function changeOrderStatus(Request $request)
        {

            $order = Order::where('order_number',$request->order_number)->with('order_details.productDetail','orderAddress','userDetail')->first();
            
            $userData = [
                    'orderNumber' =>    $order['order_number'],
                    'name' =>    $order['userDetail']['name'],
                    'email' =>    $order['userDetail']['email'],
                    'sub_total' =>    $order['sub_total'],
                    'total' =>    $order['total'],
                    'shipping_charges' =>    isset( $order['shipping_charges']) ?  $order['shipping_charges'] : "Free",
                    'coupon' =>    isset( $order['coupon']) ?  $order['coupon'] : "",
                    'discount' =>    isset( $order['discount']) ?  $order['discount'] : "",
                    'address_name' =>    $order['orderAddress']['name'],
                    'address_country' =>    $order['orderAddress']['country'],
                    'address_state' =>    $order['orderAddress']['state'],
                    'address_city' =>    $order['orderAddress']['city'],
                    'address_line1' =>    $order['orderAddress']['address_line1'],
                    'address_line2' =>    $order['orderAddress']['address_line2'],
                    'postal_code' =>    $order['orderAddress']['postal_code'],
                    'phone_number' =>    $order['orderAddress']['phone_number'],
                    'orderDate' =>    $order['payment_date'],
                    'cancellationReason' => isset($request->message ) ? $request->message  : "",
                ];
            
            
            $i = 0 ;
            $j =0 ;

        
            foreach($order['order_details'] as $value){


                $productVariation[$j] = ProductVariation::where('id', $value['product_variation'])->with('variation_items.variation_name')->with('variation_items.variation_values')->first();
                $userData['product_detail'][$i]['qty'] = $value['qty'];
                $userData['product_detail'][$i]['price'] = $value['total'];
                $userData['product_detail'][$i]['product_name'] = $value['productDetail']['name'];
                $userData['product_detail'][$j]['product_image'] = $productVariation[$j]['images'];
                $userData['product_detail'][$j]['product_variation'] = $productVariation[$j]['variation_items'];
                $i++;
                $j++;
            }
            
            if($request->status ==  "confirm")
            {
                $update['status'] = $this->confirm;


            }elseif($request->status ==  "cancel")
            {
                $update['status'] = $this->cancel;
                event(new OrderCancelMail($userData));

            }elseif($request->status ==  "dispatch")
            {
                $update['status'] = $this->dispatch;

            }elseif($request->status ==  "deliver")
            {
                $update['status'] = $this->deliver;
                $userData['client_email'] = array('bilal@prism-me.com','devteam5@prism-me.com','Hello@bafco.com');
                event(new ClientOrderDeliverMail($userData));
                event(new OrderDeliverMail($userData));

            }else{

            return response()->json('Something went Wrong');

            }

            $updateOrder = Order::where('order_number',$request->order_number)->update($update);
            return response()->json('Status Updated Successfully');

        }


    /*Product Report*/

        public function productReportList(){

            $productId = OrderDetail::with('productDetail')->get();
            $Id =  $productId->groupBy('product_id');

            $i = 0;
            $collection = [];
            foreach($Id as $key => $value)
            {
                $collection['productData'][$i] = OrderDetail::where('product_id',$key)->with('productDetail','variationDetail')->first();
                $collection['productData'][$i]['total_purchase'] = OrderDetail::where('product_id',$key)->with('productDetail','variationDetail')->sum('qty');
                $collection['productData'][$i]['total_amount'] = OrderDetail::where('product_id',$key)->with('productDetail','variationDetail')->sum('total');

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


        public function salesListFilter(Request $request){
            
            
            $results = Order::when(!empty($request->startDate), function($q){
                
                $start = Carbon::createFromFormat('Y-m-d', request('startDate'))->startOfDay();
                $q->where('created_at','>=', $start);
                
            })
            ->when(!empty($request->endDate), function($q){
                
                $end = Carbon::createFromFormat('Y-m-d', request('endDate'))->endOfDay();
                $q->where('created_at', '<=', $end);
                
            })
            ->when(count($request->all()) === 0, function($q){
                $q->dd('No record found');
            })
            ->where('status','ORDERDELIVERED')->get();
            
            
            $processedData = [];
            $c =0;

                if(empty($request->price_range)){
                    $price_min = 0;
                    $price_max = 5000; 
                }
                
                elseif($request->price_range == 1){
                    $price_min = 0;
                    $price_max = 500; 
                }
                elseif($request->price_range == 500){
                    $price_min = 501;
                    $price_max = 1000; 
                }
                else{
                    $price_min = 1001;
                    $price_max = 100000; 
                }
                
            foreach($results as $result){
                    
                if( @$price_min < @$result['sub_total'] && @$price_max >= @$result['sub_total'] ){
                    
                        $processedData[$c] = $result;
                
                    $c++;
                }
                
            }
            
            return $processedData;
            
            if($results->count() > 0){
                echo json_encode(['data' => $results , 'status'=>200]);
            }else{
                return ['data'=>'No record found.','status'=>404];
            }
    }
    


    /* End Sales*/

    /* Main Dashboard Page */

        public function dashboardDetails(){

            $totalOrder = Order::get()->count();
            $totalSales = Order::get()->sum('total');
            $totalUsers = User::get()->count();
            $totalProducts = Product::get()->count();
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

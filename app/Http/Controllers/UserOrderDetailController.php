<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\ProductVariation;
use App\Events\ClientOrderCancelMail;


class UserOrderDetailController extends Controller
{

    public function userOrderDetail($id){

        $order = Order::where('user_id',$id)->with(['order_details'=> function ($query) {
            $query->with('productDetail')->with('variationDetail');
        }])
        ->with('orderAddress')->get();


        if($order){

            return response()->json($order,200);

        }else{

            return response()->json('No Data Found');

        }
    }


    public function cancelOrder(Request $request){

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
            $userData['product_detail'][$j]['in_stock'] = $productVariation[$j]['in_stock'];

            $i++;
            $j++;
        }

        $userData['client_email'] = array('bilal@prism-me.com','devteam5@prism-me.com','Hello@bafco.com');
        event(new ClientOrderCancelMail($userData));
        $update['status'] = "ORDERCANCELLLATIONREQUEST";
        $order = Order::where('order_number',$request->order_number)->update($update);
        return response()->json('Your Order Cancellation Request Submitted Successfully!' , 200);




    }
}

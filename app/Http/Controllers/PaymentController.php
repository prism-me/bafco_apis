<?php

namespace App\Http\Controllers;

use App\Services\payment\PaymentService;
use App\Services\payment\PostPayPaymentService;
use Illuminate\Http\Request;
use App\Events\OrderPlaceMail;
use App\Events\ClientOrderPlaceMail;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Services\OrderService;

class PaymentController extends Controller
{
    private $paymentService;

    function __construct()
    {
        $this->paymentService = new PaymentService();
    }

    public function checkout(Request $request)
    {
        $data = $this->paymentService->pay(new PostPayPaymentService(), $request);
        return $data;
    }

    //guest users checkout
    public function guestCheckout(Request $request)
    {
        $result = $this->paymentService->guestCheckoutService($request);
        return $result;
    }

    //Logged in users checkout
    public function authCheckout(Request $request)
    {

        
        $result = $this->paymentService->authCheckoutService(new PostPayPaymentService(), $request);
        return $result;
    }

    //capture for success response of payment
    public function successResponse(Request $request)
    {
        $result = $this->paymentService->capturePaymentDetails(new PostPayPaymentService(), $request);
        //send email to user
        if ($result['status'] == 200 && $result['order'] == true) {
            
            $order = Order::where('order_number', $result['order_id'])->with('order_details.productDetail.productvariations', 'orderAddress', 'userDetail')->first();
            $userData = [
                'orderNumber' =>    $order['order_number'],
                'name' =>    $order['userDetail']['name'],
                'email' =>    $order['userDetail']['email'],
                'sub_total' =>    $order['sub_total'],
                'total' =>    $order['total'],
                'shipping_charges' =>    isset($order['shipping_charges']) ?  $order['shipping_charges'] : "Free",
                'coupon' =>    isset($order['coupon']) ?  $order['coupon'] : "",
                'discount' =>    isset($order['discount']) ?  $order['discount'] : "",
                'address_name' =>    $order['orderAddress']['name'],
                'address_country' =>    $order['orderAddress']['country'],
                'address_state' =>    $order['orderAddress']['state'],
                'address_city' =>    $order['orderAddress']['city'],
                'address_line1' =>    $order['orderAddress']['address_line1'],
                'address_line2' =>    $order['orderAddress']['address_line2'],
                'postal_code' =>    $order['orderAddress']['postal_code'],
                'phone_number' =>    $order['orderAddress']['phone_number'],
                'orderDate' =>    $order['payment_date'],
            ];
             $i = 0 ;
             $j = 0;
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
            event(new OrderPlaceMail($userData));
            event(new ClientOrderPlaceMail($userData));

            return redirect()->away('https://bafco.com/checkout?status=success');
        } else {
            return response()->json(['message' => 'Internal Error while payment.'], 404);
        }
    }

    public function failedResponse(Request $request)
    {

        $order = (new OrderService())->payment_failed($request->order_id, $request->status);
        
        return redirect()->away('https://bafco.com/checkout?status=cancelled'); 
    }
}
<?php

namespace App\Http\Controllers;

use App\Services\payment\PaymentService;
use App\Services\payment\PostPayPaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{   
    private $paymentService;

    function __construct(){
        $this->paymentService = new PaymentService();
    }
    
    public function checkout(Request $request){
        $data = $this->paymentService->pay(new PostPayPaymentService() , $request);
        return $data;
    }

    //guest users checkout
    public function guestCheckout(Request $request){
        $result = $this->paymentService->guestCheckoutService($request);
        return $result;
    }

    //Logged in users checkout
    public function authCheckout(Request $request){
        $result = $this->paymentService->authCheckoutService(new PostPayPaymentService() , $request);
        return $result;
    } 

    //capture for success response of payment
    public function successResponse(Request $request){
        $result = $this->paymentService->capturePaymentDetails(new PostPayPaymentService() , $request);
        return $result;
    }   

    public function failedResponse(Request $request){
        return $request->all();
    }

}

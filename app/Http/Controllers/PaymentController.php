<?php

namespace App\Http\Controllers;

use App\Services\payment\PaymentService;
use App\Services\payment\PostPayPaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function checkout(Request $request){

        $data = (new PaymentService())->pay(new PostPayPaymentService() , $request);

        return $data;

    }

    public function successResponse(Request $request){

        if(!empty($request->order_id) && $request->status === 'APPROVED'){
            $data = (new PaymentService())->capture(new PostPayPaymentService() , $request); 
        }

    }   

    public function failedResponse(Request $request){
        return $request->all();
    }




}

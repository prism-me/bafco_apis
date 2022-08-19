<?php 

namespace App\Services\payment;

use App\Interfaces\PaymentInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Postpay\Exceptions\RESTfulException;

class PostPayPaymentService implements PaymentInterface { 
    
    public function makePayment($request){
        
        $baseURL = env('POSTPAY_BASE_URL');
        $baseCode = env('POSTPAY_BASECODE');
        
        try{

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $baseCode,
            ])->post('https://sandbox.postpay.io/checkouts', $request->all());
            
             $data = $response->json();
            
            if($response->getStatusCode() == 200 && !empty($data->token)) {
               return $data->redirect_url;
            }else{
                return $data;
            }   
    }
    catch(RESTfulException $e) {
        return response()->json(['ex_message'=>$e->getMessage() , 'error'=>$e->getErrorCode(),'line' =>$e->getLine()]);
    }

}

    public function defferredPayment($request){
        return "deffered payment";
    }



}




<?php 

namespace App\Services\payment;

use App\Interfaces\PaymentInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Postpay\Exceptions\RESTfulException;

use function GuzzleHttp\Promise\all;

class PostPayPaymentService implements PaymentInterface { 

    protected $baseURL;
    protected $baseCode;

    public function __construct(){

        $this->baseURL = env('POSTPAY_BASE_URL');
        $this->baseCode = env('POSTPAY_BASECODE');
    
    }
    
    public function makePayment($request){
        
        try{

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->baseCode,
            ])->post($this->baseURL .'/checkouts', $request->all());
            
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


    public function capturePayment($request){
    
        try{
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->baseCode,
                ])->post($this->baseURL .'/orders'. '/' .$request->order_id.'/capture');
                
                 $data = $response->json();
                    return $data;
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
    // public function successResponse($data){

    //     return $data;

    // }

    // public function failedResponse($data){
    //     return $data;
    // }

    // public function defferredPayment($request){
    //     return "deffered payment";
    // }

}




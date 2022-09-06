<?php 

namespace App\Services\payment;

use App\Interfaces\PaymentInterface;
use App\Models\Cart;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Postpay\Exceptions\RESTfulException;

class PostPayPaymentService implements PaymentInterface { 

    protected $baseURL;
    protected $baseCode;
    protected $promoValidity = 1;
    public $order_number;
    public $success_url = 'http://127.0.0.1:8000/v1/api/paymentSuccess';
    public $failed_url = 'http://127.0.0.1:8000/v1/api/paymentFailed';

    public function __construct(){

        $this->baseURL = env('POSTPAY_BASE_URL');
        $this->baseCode = env('POSTPAY_BASECODE');
    
    }

    public function makePayment($request){
        
        try{

            $cartList = Cart::where('user_id',$request->user_id)->get(['product_id','product_variation_id','qty','total','unit_price']);
            
            foreach($cartList as $cart){
            
                  $cart['name'] =  Product::where('id' , $cart->product_id)->first()['name'];
                  $cart['reference'] = $cart->product_id;
                  $cart['unit_price'] = $cart->unit_price;
            }
            
            $promoCode = $this->promoCodeCheck($request->coupon_code);

            if(!$promoCode && empty($promoCode)) return response()->json('promo code is expired.' , 400);

            $this->order_number = 'OR'. rand(999 , 888888999999);
            
            $mapedObject = $this->mapPaymentObject($request->all() , $cartList , $promoCode);

            $order = (new OrderService())->createOrder($mapedObject);
            
            if(empty($mapedObject)) return response()->json('invalid data provided');

            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->baseCode,
                ])->post($this->baseURL .'/checkouts', $mapedObject);
                
                $data = $response->json();

                return ($response->getStatusCode() == 200 && !empty($data->token)) ? $data->redirect_url :  $data;

        }     
        catch(RESTfulException $e) {

            return response()->json(['ex_message'=>$e->getMessage() , 'error'=>$e->getErrorCode(),'line' =>$e->getLine()]);
            
        }
    }

    public function promoCodeCheck($coupon_code){

        $ifValid = DB::table('promo_codes')
              ->where('name', $coupon_code)
              ->whereDate('end_date', '>=', date("Y-m-d"))
              ->first();
              
              return ($ifValid) ? $ifValid : false;
  }

  public function mapPaymentObject($data , $cartList , $promoCode){

        $data['order_id'] = $this->order_number;
         $data['items'] = $cartList;
        //  $data['discounts']['code'] = $promoCode->name;
         $data['merchant']['confirmation_url'] = $this->success_url;
         $data['merchant']['cancel_url'] = $this->failed_url;
         return $data;

  }


    public function capturePayment($order_id){
        try{
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->baseCode,
                ])->post($this->baseURL .'/orders'. '/' .$order_id.'/capture');
                
                 $data = $response->json();

                if($response->getStatusCode() == 200 AND $data['order_id'] == $order_id AND $data['status'] == 'captured') {
                   return $data;
                   $order = (new OrderService())->updateOrderAfterPayment($order_id);
                }else{
                    return 'Error';
                }   
            }
            catch(RESTfulException $e) {
                return response()->json(['ex_message'=>$e->getMessage() , 'error'=>$e->getErrorCode(),'line' =>$e->getLine()]);
            }
    

    }

}

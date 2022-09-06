<?php

namespace App\Http\Controllers;

use App\Models\PromoUser;
use App\Models\PromoCode;
use App\Models\CartCalculation;
use Illuminate\Http\Request;
use Validator;
use Auth;

class PromoUserController extends Controller
{ 

    public function promoCheck(Request $request)
    {
        try {

            \DB::beginTransaction();

            $data = $request->all();
            $user  = $request->user_id;
            $code = $request['promo_code_id'];
        
            $promoUser = PromoUser::where('user_id',$user)->where('promo_code_id',$code)->first();
            $promoCode = PromoUser::where('promo_code_id',$code)->first();
        
            $Code = PromoCode::where('name',$code)->first();

         
        
            
            if($Code){
                
                $limit  = $Code['usage'];
                $promoCount = PromoUser::where('promo_code_id',$code)->count();
                
                
                if($promoCount < $limit){
                    
                    if($Code->end_date < date('Y-m-d')){
                        
                        return json_encode(['status'=>400, 'message' => 'Promo Code Expired']); 
                        
                    }else{

                        
                        if(!($promoUser = PromoUser::where('user_id',$user)->where('promo_code_id',$code)->exists())){
                    

                            //$user = PromoUser::create($data);
                            $cartCalculation  =  CartCalculation::where('user_id', $request->user_id)->first();
                            $coupon['coupon'] = $Code['name'];
                            $coupon['discounted_price'] = $Code['value'];
                            $coupon['sub_total'] = $cartCalculation['total'] - $Code['value'];
                            $cartCalculation->update($coupon);

                    \DB::commit();
                        return json_encode(['status' =>200 ,'data' => $cartCalculation  , 'message'=>'Promo Code Applied Successfully' ]);
                        
                        }else{

                            return json_encode(['status'=>400,'message' =>'You already Use This Code']); 
                        }
                        
                    }
                    
                    
                } else{
                
                    return json_encode(['status'=>400,'message' =>'Promo Code Limit Exceeded']);
                }
            
            }else{
            
            return json_encode(['status'=>400,'message' => 'Promo Code Does not Exist']); 
            
            }
        }    catch (\Exception $e) {

            \DB::rollBack();
            return response(['Product is not added.', 'stack' => $e->getMessage() , 'line' => $e->getLine()], 500);
        }
        


    }

  



}

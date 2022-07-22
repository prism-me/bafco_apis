<?php

namespace App\Http\Controllers;

use App\Models\PromoUser;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Validator;

class PromoUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
 
 
   
    public function promoCheck(Request $request)
    {
         $validator = Validator::make($request->all(), [
                'user_id' => 'required'
                
        ]);
        if($validator->fails()){
                return response()->json(['message'=> 'No User Found', 400]);
        }
        $user  = $request['user_id'];
        $code = $request['promo_code_id'];
    
        $promoUser = PromoUser::where('user_id',$user)->where('promo_code_id',$code)->first();
        $promoCode = PromoUser::where('promo_code_id',$code)->first();
     
        $Code = PromoCode::where('name',$code)->first();
        
        if($Code){
            
            $limit  = $Code['usage'];
            $promoCount = PromoUser::where('promo_code_id',$code)->count();
            
            if($promoCount < $limit){
                
                if($Code->end_date < date('Y-m-d')){
                    
                    return json_encode(['status'=>400,'message' => 'Promo Code Expired']); 

                    
                }else{
                    
                    if(empty($promoUser)){
                         
                        return json_encode(['status' =>200 ,'data' => $Code['value']  , 'message'=>'Promo Code Applied Successfully' ]);

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
        


    }

  



}

<?php


namespace App\Http\Controllers;
use App\Models\PromoCode;
use App\Models\PromoUser;
use Illuminate\Http\Request;
use App\Http\Requests\promo\PromoCodeRequest;
use Validator;

class PromoCodeController extends Controller
{

    public function index()
    {
        try{
            $promo = PromoCode::get();
            $promo->transform(function($item){
                $item->code_count = $item->code()->count();
                return $item;
            });
         
        }
        catch (\Exception $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
       
    }

    public function store(PromoCodeRequest $request)
    {
       
        try{
         

            $data = [ 
                    'name' =>  $request->name ,
                    'value' => $request->value ,
                    'usage' =>  $request->usage ,
                    'usage_per_person' =>  $request->usage_per_person ,
                    'start_date' => $request->start_date ,
                    'end_date' => $request->end_date 
            ];
        

            if(PromoCode::where('id', $request->id)->exists()){ 

                #update
                $PromoCode = PromoCode::where('id', $request->id)->update($data);

            }else{

                #create
                $PromoCode = PromoCode::create($data);
            }
            if($PromoCode){
                return  response()->json('Data has been saved.' , 200);
            }
        
        }
        catch (ModelNotFoundException  $exception) {
            return response()->json(['ex_message'=>'PromoCode Not found.' , 'line' =>$exception->getLine() ], 400);
        }
        catch (\Error $exception) {
             return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        } 
       
     

       
    }

  
    public function show(PromoCode $promoCode)
    {
       if(!$promoCode){
            return response()->json('No Record Found.' , 404);
        }
       
        return response()->json($promoCode , 200);
    }



    
    public function destroy(PromoCode $promoCode)
    {
        if($promoCode->delete()){

            return json_encode(['status' => 'Data Deleted Successfully']);
        }else{
            return json_encode(['status' => 'Something went Wrong']);

        }
    }
}
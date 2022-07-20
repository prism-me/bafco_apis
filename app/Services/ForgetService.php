<?php 

namespace App\Services;
use App\Models\PasswordReset;
use App\Events\ForgetPasswordMail;
use Illuminate\Http\Request;
use App\Models\User;
use Str;

class ForgetService {


    public static function sendToken($data){
       
        $token = Str::random(64);
        $database = PasswordReset::create([
            'email' => $data['email'], 
            'token' => $token,
            'redirect_url' => $data['redirect_url'],
        ]); 
        $user = User::where('email',$data['email'])->first();

        $userData = array(  'name' => $user['name'],
                        'email' => $data['email'], 
                        'token' => $token,
                        'redirect_url' => $data['redirect_url'],
                    );
        
        $user =  $data['email'];
       
        event(new ForgetPasswordMail($userData));
        // Mail::to($userEmail)->send(new ForgetMail($emailData));
        return json_encode(['message', 'We have e-mailed your password reset link!' ,'status' => 200]);            
       
    }

}
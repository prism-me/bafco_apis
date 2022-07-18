<?php 

namespace App\Services;

class ForgetService {


    public static function sendToken($data){

        $token = Str::random(64);
        $database = PasswordReset::create([
            'email' => $request->email, 
            'token' => $token,
            'redirect_url' => $request->redirect_url,
        ]); 

        $emailData = array( 'name' => $database['name'],
                            'token' => $database['token']
                    );
        
        $userEmail = $request->email;
        Mail::to($userEmail)->send(new ForgetMail($emailData));
        return json_encode(['message', 'We have e-mailed your password reset link!' ,'status' => 200]);            
       
    }

}
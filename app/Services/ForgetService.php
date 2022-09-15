<?php

namespace App\Services;
use App\Models\PasswordReset;
use App\Events\ForgetPasswordMail;
use Illuminate\Http\Request;
use App\Models\User;
use Str;
use Redirect;


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

    public function resetPassword($token)
    {
        $updatePassword = PasswordReset::where('token',$token)->first();
        $error = 'Token Expired!';

        if($updatePassword){
            $timeLimit = strtotime($updatePassword['created_at']) + 1800;
            if(time() > $timeLimit){
                return view('emails.reset-password', ['token' => $token] , compact('error'));
            }
            else{
                return view('emails.reset-password', ['token' => $token]);
            }
        }else{

            return view('emails.reset-password', ['token' => $token] , compact('error'));

        }
    }

    public function submitResetPassword(Request $request)
    {
        \Validator::validate($request->all(), [
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password'

        ],[
            'password.required'    =>  "Password is required",
            'password.min'    =>  "Password contains minimum 6 characters",
            'password_confirmation.required'    =>  "Password Confirmation is required",
            "password_confirmation.same" =>  "Password Mismatch!"
        ]);

        $error = 'Token Expired!';
        $token = $request->token;
        $updatePassword = PasswordReset::where([
            'token' => $request->token
        ])
        ->first();

        if(!$updatePassword){
            return view('emails.reset-password', ['token' => $token] , compact('error'));
        }

        $update = array(
            'password' => bcrypt($request->password) ,
            'password_confirmation'=> $request->password_confirmation
        );
        $user = User::where('email', $updatePassword['email'])->update($update);
        PasswordReset::where('email', $updatePassword['email'])->delete();
        $url = $updatePassword['redirect_url'];
        return Redirect::away($url);
    }

}

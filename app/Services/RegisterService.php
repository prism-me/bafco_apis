<?php

namespace App\Services;
use App\Events\RegisterCreate;
use Illuminate\Http\Request;
use App\Models\TempUser;
use App\Models\User;
use Str;
use DB;


class RegisterService {

    public function registerUser($data){
       
        $check = User::where('email','=',$data['email'])->count();
        $user = User::where('email','=',$data['email'])->get();

        if($check > 0 && $data->is_social){
            
            
            $dd = ['name'=> $data->name , 'email'=>$data->email ,'password'=>$data->password];
            $check = ['email' =>$data->email ,'password'=>$data->password];
            $token =  $user->createToken($user->email)->plainTextToken;
            return response()->json($user , 200)->header('x_auth_token',$token)->header('access-control-expose-headers' , 'x_auth_token');
            
        }else{
            $userData['name'] = $data['name'];
            $userData['email'] = $data['email'];
            $userData['password'] = bcrypt($data['password']);
            $userData['token'] = Str::random(64);
            $userData['isActive'] = 0;
            $userData['redirect_url'] = $data['redirect_url']; 
            $userCreate = TempUser::create($userData);          
            event(new RegisterCreate($userData));
            return json_encode(['message' => 'E-Mail Verification has been sent to your registered account!','status' => 200]);
        }
       
    }

    public function tokenVerify($token){

        $user = TempUser::where('token',$token)->first();
        $error = 'Token Expired!';

        if($user){
            $timeLimit = strtotime($user['created_at']) + 1800;
            if(time() > $timeLimit){
                return view('verifyEmail', ['token' => $token] , compact('error'));
            }
            else{
                return view('verifyEmail', ['token' => $token]);
            }
        }else{

            return view('verifyEmail', ['token' => $token] , compact('error'));

        }
    }

    public function registerVerify($data){

        $user = TempUser::where('token', $data->token )->first();
        $error = 'Token Expired!';
        
        if(!$user){
            return view('loading', ['token' =>  $data->token ] , compact('error'));
        }
            $user['isActive'] =  1 ;
            $user['token'] =  "" ;
            
            $tempUser = $user->update(['token'=> "" , "isActive" => 1]);
            $create = array(
                "name" => $user['name'],
                "firstName" => $user['firstName'],
                "lastName" => $user['lastName'],
                "mobile" => $user['mobile'],
                "address" => $user['address'],
                "area" => $user['area'],
                "emirates" => $user['emirates'],
                "email" => $user['email'],
                "password" => $user['password'],
                "password_confirmation" => $user['password_confirmation'],
                "isActive" => $user['isActive'],
                "user_type" => $user['user_type'],
                "kid_age" => $user['kid_age'],
                "pregnent" => $user['pregnent']
            );
            
            $userCreate =  User::create($create);
            $url = $user['redirect_url'];
            $user->delete();
            return Redirect::away($url);
    }


}
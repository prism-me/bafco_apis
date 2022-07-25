<?php

namespace App\Services;
use App\Events\RegisterCreate;
use Illuminate\Http\Request;
use App\Models\TempUser;
use App\Models\User;
use Str;
use DB;
use Redirect;


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
            $userData['isActive'] = 0;
            $fourRandomDigit = rand(10000,99999);
            $userData['code'] = $fourRandomDigit;
            $userCreate = TempUser::create($userData);
            event(new RegisterCreate($userData));
            return json_encode(['message' => 'E-Mail Verification has been sent to your registered account!','status' => 200]);
        }

    }

    public function tokenVerify($code){

          $user = TempUser::where('code',$code)->first();
          if($user){
            $timeLimit = strtotime($user['created_at']) + 1800;
            if(time() > $timeLimit){
                return json_encode('Token Expired');
           }
            else{
                $tempUser = TempUser::where('isActive' , 0)->delete();
                $userCreate =  User::create($user);

                return json_encode('Register Successfully');
            }
        }else{
              return json_encode('Token Expired');

       }
    }




}

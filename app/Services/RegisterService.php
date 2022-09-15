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
            $userData['password'] = $data['password'];
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
                $user->delete();
                return response()->json(['error' =>'Token Expired',400]);
            }else{

                $create = [
                        'name' => $user->name,
                        'email' => $user->email,
                        'password' => bcrypt($user->password)
                ];


                $userCreate =  User::firstOrcreate($create);
                $usersDetail  = $user;
                $user->delete();
                return $usersDetail;
            }
          }else{
                  return response()->json(['error' => 'Invalid Token!',400]);

           }
    }




}

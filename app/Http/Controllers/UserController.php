<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\user\LoginRequest;
use App\Http\Requests\user\ResetRequest;
use App\Http\Requests\user\RegisterRequest;
use App\Http\Requests\user\ForgetRequest;
use App\Http\Requests;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\PasswordReset;
use App\Mail\ForgetMail;
use App\Services\ForgetService;
use App\Services\RegisterService;
use App\Services\UserService;
use DateTime;
use Redirect;
use Validator;
use Session;
use Hash;
use Auth;
use Mail;
use DB;

class UserController extends Controller
{

    ####Register#####
    public function register(RegisterRequest $request) {

        try{

            $data = $request->all();
            $user = RegisterService::registerUser($data);
            return $user;
            if($user){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (\Error $exception) {
             return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }



    }

    public function emailVerify(Request $request)
    {
        try{
            $data = $request->all();

            return RegisterService::tokenVerify($data);
            if($user){
                return  response()->json('Data has been saved.' , 200);
            }
        }
        catch (\Error $exception) {
             return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }



    }
    ####End Register#####
    public function login(LoginRequest $request){

        try{

           if (!Auth::attempt(['email'=>$request->email, 'password'=>$request->password])) {

                return response()->json('Credentials does not match', 401);

            }

            $token = auth()->user()->createToken('API_Token')->plainTextToken;

            return response()->json('Logged in successfully', 200)->header('x_auth_token', $token)->header('access-control-expose-headers' , 'x_auth_token');

        } catch(BadMethodCallException $e){

            return response()->json('Email/Password is invalid.', 404);

        }


        // $user = User::where('email', $request->email)->first();

        // if (! $user || ! Hash::check($request->password, $user->password)) {
        //     return response()->json(['error' => 'The provided credentials are incorrect.'] ,422);
        // }
        // $token =  $user->createToken($request->email)->plainTextToken;

        // return response()->json('Logged in successfully', 200)->header('x-auth-token', $token);

    }

    public function me(){

        return auth()->user();
    }


    public function updateProfile(ResetRequest $request){
        try{

            $data = $request->all();
            $user = UserService::update($data);
            return $user;
            if($user){
                return response()->json('Updated Successfully',200);
            }


        } catch(BadMethodCallException $e){

            return response()->json('Email/Password is invalid.', 404);

        }
    }


    #####Forget Password#####
    public function forgetPassword(ForgetRequest $request){

        try{

            $forget = ForgetService::sendToken($request->all());
            return $forget;
            if($forget){

                return  response()->json('Data has been saved.' , 200);
            }
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }



    }

    public function resetPassword($token)
    {
        try{

            $forget = ForgetService::resetPassword($token);
            return $forget;
            if($forget){

                return  response()->json('Data has been saved.' , 200);
            }
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }
    }

    public function submitResetPassword(ForgetRequest $request)
    {
        try{
            $data = $request->all();
            $forget = ForgetService::submitResetPassword($data);
            return $forget;
            if($forget){

                return  response()->json('Data has been saved.' , 200);
            }
        }
        catch (\Error $exception) {
            return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400);
        }


    }
    #####End Forgot Password######


    public function logout()
    {

        dd(auth()->user());
        // $user->tokens()->where('id', $tokenId)->delete();

        if(!auth()->user()->tokens()->delete()) return response()->json('Server Error.', 400);

        return response()->json('You are logged out successfully', 200);
    }


}

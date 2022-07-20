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


    public function emailVerify($token)
    {
        try{

            $data = $token;
            $user = RegisterService::tokenVerify($data);
            return $user;
            if($user){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (\Error $exception) {
             return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }
        
        
        
    }

    public function verifyEmail(Request $request)
    {
        try{

            $data = $request->all();
            $user = RegisterService::registerVerify($data);
            return $user;
            if($user){
                return  response()->json('Data has been saved.' , 200);
            }

        }
        catch (\Error $exception) {
             return response()->json(['ex_message'=> $exception->getMessage() , 'line' =>$exception->getLine()], 400); 
        }

        

    }



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


    public function userDetail(Request $request){
        $user = User::select('name','email','is_social','profile')->where('_id',$request->user_id)->first();
        return $user;
    }
    

    public function updateUser(Request $request)
    {
        $user = $request['user_id'];
        $input = $request->except('_id');
        if($update){
            
            echo json_encode(['message','Profile Updated successfully','status'=>200]);
            
        }else{
            
            echo json_encode(['message','Server Error While']);

        }
    }


    public function reset(ResetRequest $request)
    {
        try{

            $check = ['email' => $request->email,'password'=>$request->password];
            
            if( $token = auth()->attempt($check)){
                $data = ['password'=> bcrypt($request->changed_password)];
                $isUserUpdated = User::where('email',$request->email)->update($data);

                if($isUserUpdated){

                    echo json_encode(['message' => 'Password has been changed successfully.']);

                }else{
                echo json_encode(['message' => 'Password is not changed , server error']);
                }


            }else{

                echo json_encode(['message'=>'Credetials doesnot match.']);
            }

        } catch(BadMethodCallException $e){

            return response()->json('Email/Password is invalid.', 404);

        }
    }


    #Forget Password
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


    public function logout()
    {   

        dd(auth()->user());
        // $user->tokens()->where('id', $tokenId)->delete();

        if(!auth()->user()->tokens()->delete()) return response()->json('Server Error.', 400);
        
        return response()->json('You are logged out successfully', 200);
    }


}

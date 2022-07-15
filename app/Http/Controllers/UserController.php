<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
use Hash;
use Validator;
use Auth;
use App\Http\Requests\user\LoginRequest;
use App\Http\Requests\user\ResetRequest;
use App\Http\Requests\user\RegisterRequest;

class UserController extends Controller
{   
    
    public function register(RegisterRequest $request){
        
        $request['password'] = bcrypt($request->password);
        
        $user = User::create($request->all());

        $token =  $user->createToken($user->email)->plainTextToken;

        return response()->json($user , 200)->header('x_auth_token',$token)->header('access-control-expose-headers' , 'x_auth_token');

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


    public function logout()
    {   

        dd(auth()->user());
        // $user->tokens()->where('id', $tokenId)->delete();

        if(!auth()->user()->tokens()->delete()) return response()->json('Server Error.', 400);
        
        return response()->json('You are logged out successfully', 200);
    }


}

<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use App\Traits\HandelResApi;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthController extends Controller
{
    use HandelResApi;

    public function __construct()
    {
        $this->middleware('auth.api:user-api')->only('logout');

    }
    public function login(Request $request){
        $validate= Validator::make($request->all(), $this->rulesLogin());

        if($validate->fails()){
            $data=$validate->errors()->toArray();
            return $this->resApi(false,'Error verifying data',$data);
        }

        $credential=$request->only('email','password');
        $token=auth('user-api')->attempt($credential);

        if(!$token){
            return $this->resApi(false,'Email Or Password Wrong');
        }

        $data=auth('user-api')->user();
        $data->token=$token;
        return $this->resApi(true,'You are logged in successfully',$data);



    }


    public function register(Request $request){
        $validate=Validator::make($request->all(),$this->rulesRegister());
        if($validate->fails()){

            $data= $validate->errors()->toArray();
            return $this->resApi(false,'Error verifying data', $data);
        }

        $findUser=User::whereEmail($request->email)->first();
        if($findUser){

            return $this->resApi(false,'The email already exists');
        }
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);


        $credential=$request->only(['email','password']);
        $token=auth('user-api')->attempt($credential);
        $data=auth('user-api')->user();
        $data->token=$token;
        return $this->resApi(true,'You are logged in successfully',$data);
    }


    public function logout(Request $request){
        $token= $request->header('authorization');
        $token=str_replace('Bearer ','',$token);

        try{
            JWTAuth::setToken($token)->invalidate(true);
            return $this->resApi(true,'You are logout in successfully');
        }catch(\Exception $e){
            return $this->resApi(false,$e->getMessage());
        }

    }


    private function rulesLogin(){
        return [
            'email'=>'required|email',
            'password'=>'required|string',
        ];
    }

    private  function rulesRegister(){

        return [
            'name'=>'required|string|max:100',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string',
        ];
    }
}

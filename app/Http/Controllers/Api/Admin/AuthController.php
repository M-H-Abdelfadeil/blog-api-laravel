<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HandelResApi;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use HandelResApi;
    public function __construct()
    {
        $this->middleware('auth.api:admin-api')->only('logout');
        
    }
    public function login(Request $request){
        $validate=Validator::make($request->all(),$this->rulesLogin());
        if($validate->fails()){

            $data= $validate->errors()->toArray();
            return $this->resApi(false,'Error verifying data', $data);
        }

        $credential=$request->only(['email','password']);
        $token=auth('admin-api')->attempt($credential);

        if(!$token){
            return $this->resApi(false,'Email or password wrong');
        }

        $data=auth('admin-api')->user();
        $data->token=$token;
        return $this->resApi(true,'You are logged in successfully', $data);

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


    private  function rulesLogin(){

        return [
            'email'=>'required|email',
            'password'=>'required|string',
        ];
    }



}

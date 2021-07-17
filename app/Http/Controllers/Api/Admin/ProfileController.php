<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicResource\PostsUserResource;
use App\Http\Resources\PublicResource\UserResource;
use App\Models\Admin;
use App\Models\Post;
use App\Models\User;
use App\Traits\HandelResApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
class ProfileController extends Controller
{

    use HandelResApi;
    public function __construct(){
        $this->middleware('auth.api:admin-api');
    }

    public function index(){
        $user=Admin::whereId(auth('admin-api')->id())->first();
        return $this->resApi(true,'',new UserResource($user));
    }

    public function edit(){
        $user=Admin::whereId(auth('admin-api')->id())->first();
        return $this->resApi(true,'',new UserResource($user));

    }
    public function update(Request $request){
        $validate=Validator::make($request->all(),$this->rules(auth('admin-api')->id()));
        if($validate->fails()){

            $data= $validate->errors()->toArray();
            return $this->resApi(false,'Error verifying data', $data);
        }

      Admin::whereId(auth('admin-api')->id())->update([
            'name'=>$request->name,
            'email'=>$request->email,
        ]);

        return $this->resApi(true,'Your profile has been successfully modified');


    }
    public function updatePassword(Request $request){
        $validate=Validator::make($request->all(),$this->rulesPassword());
        if($validate->fails()){

            $data= $validate->errors()->toArray();
            return $this->resApi(false,'Error verifying data', $data);
        }

      Admin::whereId(auth('admin-api')->id())->update([
            'password'=>Hash::make($request->password),
        ]);

        return $this->resApi(true,'Your password has been successfully modified');
    }
    public function delete(Request $request){

        Admin::whereId(auth('admin-api')->id())->delete();

        try{
            $token= $request->header('authorization');
            $token=str_replace('Bearer ','',$token);
            JWTAuth::setToken($token)->invalidate(true);
            return $this->resApi(true,'Your profile has been successfully deleted');
        }catch(\Exception $e){
            return $this->resApi(false,$e->getMessage());
        }
    }




    private  function rules($id){

        return [
            'name'=>'required|string|max:100',
            'email'=>'required|email|unique:admins,email,'.$id,
        ];
    }

    private  function rulesPassword(){

        return [
            'password'=>'required|string|confirmed|min:8',
        ];
    }


}

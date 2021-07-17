<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicResource\UserResource;
use App\Models\Admin;
use App\Models\Post;
use App\Models\User;
use App\Traits\HandelResApi;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;

class MainController extends Controller
{
    use HandelResApi;
    public function __construct(){
        $this->middleware('auth.api:admin-api');
    }

    public function deleteUser(Request $request){

        $validate= Validator::make($request->all(),['user_id'=>'required|numeric|exists:users,id']);

        if($validate->fails()){

            return $this->resApi(false,'Error verifying data',$validate->errors()->toArray());
        }
       User::whereId($request->user_id)->delete();
       return $this->resApi(true,'Your user has been successfully deleted');

    }

    public function deletePost(Request $request){

        $validate= Validator::make($request->all(),['post_id'=>'required|numeric|exists:posts,id']);

        if($validate->fails()){

            return $this->resApi(false,'Error verifying data',$validate->errors()->toArray());
        }
       Post::whereId($request->post_id)->delete();
       return $this->resApi(true,'Your post has been successfully deleted');

    }

    public function showAdmins(){
        $admins=Admin::get();
        if(!$admins){
            return $this->resApi(false,'There are no admins');
        }
        return $this->resApi(true,'',UserResource::collection($admins));
    }

    public function createAdmin(Request $request){
        $validate=Validator::make($request->all(),$this->rules());
        if($validate->fails()){

            $data= $validate->errors()->toArray();
            return $this->resApi(false,'Error verifying data', $data);
        }

        $admin=Admin::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        return $this->resApi(true,'The admin has been created successfully',new UserResource($admin));

    }




    private  function rules(){

        return [
            'name'=>'required|string|max:100',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|confirmed',
        ];
    }
}

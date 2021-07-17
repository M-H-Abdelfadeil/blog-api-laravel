<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicResource\PostResource;
use App\Models\Post;
use App\Traits\HandelResApi;
use Illuminate\Http\Request;
use Validator;

class PostControllrt extends Controller
{
    use HandelResApi;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {

        $this->middleware('auth.api:user-api')->except(['index','show']);

    }
    public function index()
    {
        $posts=Post::with('user')->paginate(env('COUNT_PAGINATE',10));

        if($posts){
            return $this->resApi(true,'', PostResource::collection($posts));
        }else{
            return $this->resApi(false,'There are no posts');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        // check data
        $validate=Validator::make($request->all(),$this->rules());
        if($validate->fails()){
            $data=$validate->errors()->toArray();
            return $this->resApi(false,'Error verifying data', $data);
        }

        // create post
        $post=Post::create([
            'title'=>$request->title,
            'content'=>$request->content,
            'user_id'=>auth('user-api')->id(),
        ]);

        return $this->resApi(true,'Post created successfully',new PostResource( $post));


    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $post = Post::whereId($id)->first();
        if($post){
            return $this->resApi(true,'Post created successfully',new PostResource( $post));
        }

        return $this->resApi(false,'Not found');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::whereId($id)->whereUserId(auth('user-api')->id())->first();
        if($post){

            return $this->resApi(true,'',new PostResource( $post));
        }

        return $this->resApi(false,'Not found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::whereId($id)->whereUserId(auth('user-api')->id())->first();
        if($post){

            // check data
            $validate=Validator::make($request->all(),$this->rules());
            if($validate->fails()){
                $data=$validate->errors()->toArray();
                return $this->resApi(false,'Error verifying data', $data);
            }

            $post->update([
                'title'=>$request->title,
                'content'=>$request->content,
                'user_id'=>auth('user-api')->id(),
            ]);
            return $this->resApi(true,'',new PostResource( $post));
        }

        return $this->resApi(false,'Not found');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $post = Post::whereId($id)->whereUserId(auth('user-api')->id())->first();
        if($post){
            $post->delete();
            return $this->resApi(true,'Post deleted successfully');
        }

        return $this->resApi(false,'Not found');
    }




    private function rules(){
        return [
            'title'=>'required|string|max:100',
            'content'=>'required|string|max:5000'
        ];
    }
}

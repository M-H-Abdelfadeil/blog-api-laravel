<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });





Route::group(['namespace'=>'Api'],function () {



    //routes Dashboard
    Route::group(['namespace'=>'Admin','prefix'=>'dashboard'],function(){
        // routes  auth
        Route::post('login'     ,'AuthController@login');
        Route::post('logout'    ,'AuthController@logout');

        // routes profile

        Route::get('profile','ProfileController@index');
    });



    //routes User
    Route::group(['namespace'=>'User'],function(){

        // routes  auth
        Route::post('login'     ,'AuthController@login');

        Route::post('register'  ,'AuthController@register');

        Route::post('logout'    ,'AuthController@logout');




        // routes profile
        Route::get('profile'                     ,'ProfileController@index');

        Route::get('edit-profile'                ,'ProfileController@edit');

        Route::post('update-profile'             ,'ProfileController@update');

        Route::post('update-password-profile'    ,'ProfileController@updatePassword');

        Route::post('delete-profile'             ,'ProfileController@delete');




        // routes posts

        Route::get('posts'              ,'PostControllrt@index');

        Route::get('show-post/{id}'     ,'PostControllrt@show');

        Route::post('create-post'       ,'PostControllrt@create');

        Route::get('edit-post/{id}'     ,'PostControllrt@edit');

        Route::post('update-post/{id}'  ,'PostControllrt@update');

        Route::post('delete-post/{id}'  ,'PostControllrt@delete');






    });
});





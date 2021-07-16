<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HandelResApi;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use HandelResApi;
    public function __construct(){
        $this->middleware('auth.api:admin-api');
    }
    public function index(){
        return $this->resApi(true,'',auth('admin-api')->user());
    }
}

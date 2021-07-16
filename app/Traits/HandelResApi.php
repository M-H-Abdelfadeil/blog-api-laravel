<?php
namespace App\Traits;
trait  HandelResApi{


    public function resApi($status,$msg,$data=null){
        return response()->json([
            'status'=>$status,
            'msg'=>$msg,
            'data'=>$data,
        ]);
    }

}

<?php

namespace App\Http\Middleware;

use App\Traits\HandelResApi;
use Closure;

class AuthApi
{
    use HandelResApi;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard=null)
    {
        if($guard != null){
            auth()->shouldUse($guard);
            try{

               $user= auth()->authenticate();
            }catch(\Exception $e){
                return $this->resApi(false,'Unauthenticated');
            }
        }
        return $next($request);
    }
}

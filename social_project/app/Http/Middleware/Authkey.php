<?php

namespace App\Http\Middleware;

use Closure;

class Authkey
{
    //handle comming requests
    //check api authintication
    public function handle($request, Closure $next)
    {
        $access_token = $request->header('token');
        $user = Auth::user();
        if( $user && $access_token == $user->api_token)
        {
            return $next($request);
        }
        return  \response()->json(['meaasge' => 'You are unauthorized!'] , 401);  //unauthorized
        
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class tokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if (Cache::get('jwt_cache_token')) {
        //     $token = Cache::get('jwt_cache_token');
        //response->headers->set('Authorization', 'Bearer '.$token);
        //return $next($request)->headers('Authorization', ' Bearer '.$token);
        //$response = $next($request);
        //$response->header('Authorization', ' Bearer '.$token);
        //$request->header('Authorization', ' Bearer '.$token);
        //$request->header('Authorization', 'Bearer '.$token);

        //return $next($request);

        //$response = $next($request);

        // $response->header('Authorization', 'Bearer '.$token);
        // return $next($request)->header('Access-Control-Allow-Origin', '*')
        //    ->header('Access-Control-Max-Age', '86400')
        //    ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With')
        //    ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE, PATCH')
        //    ->header('Authorization', 'Bearer '.$token);

        // return $next($request)->header('Authorization', 'Bearer '.$token);
        // return $response;
        // $request->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With');

        //$response = $next($request);
        //$response->header('header name', 'header value');
        //$response->header('another header', 'another value');
        // $request->headers->set('Accept', 'application/json');
        // $request->headers->set('Authorization', 'Bearer '.$token);
        //header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE, PATCH');

        //return $next($request);
        //$request->headers->set('Authorization', 'Bearer '.$token);

        //return $next($request);
        //header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE, PATCH');
        // } else {
        //     return $next($request);
        // }

        $request->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin');
        $request->headers->set('Authorization', '11111');
        $request->headers->set('content-type', 'application/x-www-form-urlencoded');

        // return $next($request)->withHeaders([
        //     'Authorization' => 'Bearer 33333',
        // ]);

        $response = $next($request);

        $response->header('Authorization', '33333');

        return $response;
    }
}

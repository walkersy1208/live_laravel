<?php

namespace App\Http\Middleware;

use App\admin\Admin;
use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class requestHeader
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
        //header('11111');
        //$req_path = $request->path();
        //dd(Auth::guard('admin_jwt')->payload());
        //$auth_backend_user_id = auth('admin_jwt')->parseToken();

        //dd($user = JWTAuth::parseToken()->authenticate());
        //dd($auth_backend_user_id);
        //$token = JWTAuth::fromUser(1);
        //$token = JWTAuth::parseToken()->getToken();
        //$this->auth = JWTAuth::parseToken()->authenticate();
        //$auth_user_id = JWTAuth::parseToken()->getPayload()->toArray()['sub'];
        // $token = auth('admin_jwt')->tokenById(1);
        // if (!empty($token)) {
        //     $request->headers->set('Authorization', 'Bearer '.$token);
        // }

        return $next($request);
    }
}

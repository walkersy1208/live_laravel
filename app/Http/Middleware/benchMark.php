<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class benchMark
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
        $sTime = microtime(true);

        $response = $next($request);
        $runTime = microtime(true) - $sTime;
        Log::info('message', [
            'url' => $request->url(),
            'params' => $request->input(),
            'time' => $runTime,
        ]);

        return $response;
    }
}

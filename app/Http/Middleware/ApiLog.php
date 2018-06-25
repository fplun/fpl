<?php

namespace App\Http\Middleware;

use Closure;
use Log;

class ApiLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info("request {$request->url()}:", $request->all());
        $response = $next($request);
        if ($response instanceof \Illuminate\Http\JsonResponse ) {
            Log::info("response {$request->url()}:", $response->getData(true));
        }
        return $response;
    }
}
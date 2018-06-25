<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class WebAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uid = $request->session()->get('uid');
        if (!$uid) {
            return redirect('login');
        }
        $user = User::findOrFail($uid);
        $request->user = $user;
        
        return $next($request);
    }
}

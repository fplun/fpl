<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class ApiAuth
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
        if (!($token = $request->header('Token')) || strpos($token, '.') === false) {
            return response()->json([
                'code' => 403,
                'message' => '请先登录',
            ]);
        }

        list($uid, $token) = explode('.', $request->token);
        $user = User::where('id', $uid)->where('token', $token)->first();
        if (!$user) {
            return response()->json([
                'code' => 403,
                'message' => '请先登录',
            ]);
        }
        //被禁用做判断
        if ($user->status == User::STATUS_DISABLED) {
            return response()->json([
                'code' => 401,
                'message' => '您已被禁用！',
            ]);
        }
        //未激活
        if (empty($user->pid)) {
            return response()->json([
                'code' => 401,
                'message' => '账号未激活！',
            ]);
        }
        $request->user = $user;
        return $next($request);
    }
}

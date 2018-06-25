<?php

namespace App\Http\Middleware;

use Closure;

class Deal
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
        $initial_time=strtotime(date('Y-m-d',time()));
        //每天10点
        $start_time=$initial_time+36000;
        //每天17点
        $end_time=$initial_time+61200;
        /* if($start_time > time() || $end_time < time()){
            return redirect('/center/index')->with(['code'=>2,'message'=>'交易中心开放时间为每天10:00-17:00']);
        } */
        return $next($request);
    }
}

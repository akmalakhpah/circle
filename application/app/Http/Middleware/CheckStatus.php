<?php
/*
* My Circle: Performance Management System
* Email: circle@aidan.my
* Version: 1.0
* Author: Akmal Akhpah
* Copyright 2019 Aidan Technologies
* Website: https://github.com/akmalakhpah/circle
*/
namespace App\Http\Middleware;

use Closure;

class CheckStatus
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
        $status = \Auth::user()->status;
        if ($status==null || $status==0){
            \Auth::logout();
            return redirect('account-disabled');
        } 

        $role_id = \Auth::user()->role_id;
        if ($role_id==null || $role_id==0){
            \Auth::logout();
            return redirect('account-not-found');
        }

        return $next($request);
    }
}

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
use Auth;
use Closure;

class Employee
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
        $type = \Auth::user()->acc_type;
        // viceversa Employee / Admin
        if ($type == '1' || $type == '2'){
            // nothing
        } else {
            Auth::logout(); 
            return redirect('permission-denied');
        }

        return $next($request);
    }
}

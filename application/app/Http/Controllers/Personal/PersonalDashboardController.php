<?php
/*
* My Circle: Performance Management System
* Email: circle@aidan.my
* Version: 1.0
* Author: Akmal Akhpah
* Copyright 2019 Aidan Technologies
* Website: https://github.com/akmalakhpah/circle
*/
namespace App\Http\Controllers\personal;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class PersonalDashboardController extends Controller
{
    public function index() {
        $id = \Auth::user()->reference;
        $sm = date('m/01/Y');
        $em = date('m/31/Y');

        $cs = table::schedules()->where([
            ['reference', $id], 
            ['archive', '0']
        ])->first();

        $ps = table::schedules()->where([
            ['reference', $id],
            ['archive', '1'],
        ])->take(8)->get();

        $al = table::leaves()->where([['reference', $id], ['status', 'Approved']])->count();
        $ald = table::leaves()->where([['reference', $id], ['status', 'Approved']])->take(8)->get();
        $pl = table::leaves()->where([['reference', $id], ['status', 'Declined']])->orWhere('status', 'Pending')->count();
        $a = table::attendance()->where('reference', $id)->latest('date')->take(4)->get();

        $la = table::attendance()->where([['reference', $id], ['status_timein', 'Late Arrival']])->whereBetween('date', [$sm, $em])->count();
        $ed = table::attendance()->where([['reference', $id], ['status_timeout', 'Early Departure']])->whereBetween('date', [$sm, $em])->count();

        return view('personal.personal-dashboard', compact('cs', 'ps', 'al', 'pl', 'ald', 'a', 'la', 'ed'));
    }
}


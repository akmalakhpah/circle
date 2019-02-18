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


class PersonalSchedulesController extends Controller
{
    public function index() {
        $i = \Auth::user()->idno;
        $s = table::schedules()->where('idno', $i)->get();
        return view('personal.personal-schedules-view', compact('s'));
    }

    public function getPS(Request $request) {
        $id = \Auth::user()->idno;
        $datefrom = $request->datefrom;
		$dateto = $request->dateto;
		
		if($datefrom == '' || $dateto == '' ) {
            $data = table::schedules()
            ->select('intime', 'outime', 'datefrom', 'dateto', 'hours', 'restday', 'archive')
            ->where('idno', $id)
            ->get();
            return response()->json($data);

		} elseif ($datefrom !== '' AND $dateto !== '') {
            $data = table::schedules()
            ->select('intime', 'outime', 'datefrom', 'dateto', 'hours', 'restday', 'archive')
            ->where('idno', $id)
            ->whereBetween('datefrom', [$datefrom, $dateto])
            ->get();
            return response()->json($data);
        } 
    }
}


<?php
/*
* My Circle: Performance Management System
* Email: circle@aidan.my
* Version: 1.0
* Author: Akmal Akhpah
* Copyright 2019 Aidan Technologies
* Website: https://github.com/akmalakhpah/circle
*/
namespace App\Http\Controllers\admin;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class SchedulesController extends Controller
{
    public function index() {
        if (permission::permitted('schedules')=='fail'){ return view('errors.permission-denied'); }
        
        $employee = table::people()->get();
        $schedules = table::schedules()->get();
    
        return view('admin.schedules', compact('employee', 'schedules'));
    }

    public function add(Request $request) {
        if (permission::permitted('schedules-add')=='fail'){ return view('errors.permission-denied'); }

    	$id = $request->id;
		$employee = mb_strtoupper($request->employee);
		$intime = $request->intime;
		$outime = $request->outime;
		$datefrom = $request->datefrom;
		$dateto = $request->dateto;
		$hours = $request->hours;
        $restday = implode(', ', $request->restday);
        
        if ($employee == '' || $intime == '' || $outime == '' || $id == '') {
        	return redirect('schedules')->with('error', 'Whoops! Please fill the form completely.');
        } 

        $ref = table::schedules()->where([['reference', $id],['archive', 0]])->exists();

        if ($ref == 1) {
            return redirect('schedules')->with('error', 'Oops! This employee has schedule already. Please arhive the present schedule to add new schedule.');
        }

        $emp_id = table::companydata()->where('reference', $id)->value('idno');
    
        table::schedules()->where('id', $id)->insert([
        	'reference' => $id,
        	'idno' => $emp_id,
        	'employee' => $employee,
        	'intime' => $intime,
        	'outime' => $outime,
        	'datefrom' => $datefrom,
        	'dateto' => $dateto,
        	'hours' => $hours,
        	'restday' => $restday,
        	'archive' => '0',
    	]);

    	return redirect('schedules')->with('success', 'New Schedule Added!');
	}

    public function edit($id, Request $request) {
        if (permission::permitted('schedules-edit')=='fail'){ return view('errors.permission-denied'); }

        $s = table::schedules()->where('id', $id)->first();
        $r = explode(', ', $s->restday);
        
        return view('admin.edits.edit-schedule', compact('s','r'));
    }

    public function update(Request $request) {
        if (permission::permitted('schedules-edit')=='fail'){ return view('errors.permission-denied'); }

        $id = $request->id;
        $intime = $request->intime;
        $outime = $request->outime;
        $datefrom = $request->datefrom; 
        $dateto = $request->dateto; 
        $hours = $request->hours;
        $restday = implode(', ', $request->restday);

        if ($id == null || $intime == null || $outime == null || $datefrom == null || $dateto == null || $restday == null) {
            return redirect('schedules')->with('error', 'Whoops! Please fill the form completely.');
        }

        table::schedules()
        ->where('id', $id)
        ->update([
                'intime' => $intime,
                'outime' => $outime,
                'datefrom' => $datefrom,
                'dateto' => $dateto,
                'hours' => $hours,
                'restday' => $restday,
        ]);

        return redirect('schedules')->with('success', 'Schedule has been updated!');
    }

    public function delete($id) {
        if (permission::permitted('schedules-delete')=='fail'){ return view('errors.permission-denied'); }

        table::schedules()->where('id', $id)->delete();

        return redirect('schedules')->with('success', 'Deleted!');
    }

    public function archive($id, Request $request)
    {
		if (permission::permitted('schedules-archive')=='fail'){ return view('errors.permission-denied'); }

		$id = $request->id;
		table::schedules()->where('id', $id)->update(['archive' => '1']);

    	return redirect('/schedules')->with('success','Schedule has been archived.');
   	}


}
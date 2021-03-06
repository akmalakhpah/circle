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


class DashboardController extends Controller
{

    public function index() {
        if (permission::permitted('dashboard')=='fail'){ return view('errors.permission-denied'); }
        
        $datenow = date('m/d/Y');
        $setting = table::settings()->first();
            
        $emp_all_type = table::people()
        ->join('tbl_company_data', 'tbl_people.id', '=', 'tbl_company_data.reference')
        ->where('tbl_people.employmentstatus', 'Active')
        ->orderBy('tbl_company_data.startdate', 'desc')
        ->take(4)
        ->get();

        $emp_birthday = table::people()
        ->join('tbl_company_data', 'tbl_people.id', '=', 'tbl_company_data.reference')
        ->where('tbl_people.employmentstatus', 'Active')
        ->whereRaw('DAYOFYEAR(curdate()) - 2 <= DAYOFYEAR(tbl_people.birthday)')
        ->orderByRaw('DAYOFYEAR(tbl_people.birthday)')
        ->take(4)
        ->get();

        $emp_typeR = table::people()
        ->where('employmenttype', 'Regular')
        ->where('employmentstatus', 'Active')
        ->count();

        $emp_typeC = table::people()
        ->where('employmenttype', 'Contract')
        ->where('employmentstatus', 'Active')
        ->count();

        $emp_typeT = table::people()
        ->where('employmenttype', 'Trainee')
        ->where('employmentstatus', 'Active')
        ->count();

        $emp_allActive = table::people()
        ->where('employmentstatus', 'Active')
        ->count();

        if($setting->enable_attendance){
            $is_online = table::attendance()->where('date', $datenow)->pluck('idno');
            $is_online_arr = json_decode(json_encode($is_online), true);
            $is_online_now = count($is_online); 

            $emp_ids = table::companydata()->pluck('idno');
            $emp_ids_arr = json_decode(json_encode($emp_ids), true); 
            $is_offline_now = count(array_diff($emp_ids_arr, $is_online_arr));

            $a = table::attendance()->latest('date')->take(4)->get();
            
            $emp_approved_leave = table::leaves()
            ->where('status', 'Approved')
            ->orderBy('leavefrom', 'desc')
            ->take(4)
            ->get();

    		$emp_leaves_approve = table::leaves()
            ->where('status', 'Approved')
            ->count();

    		$emp_leaves_pending = table::leaves()
            ->where('status', 'Pending')
            ->count();

    		$emp_leaves_all = table::leaves()
            ->where('status', 'Approved')
            ->orWhere('status', 'Pending')
            ->count();
        }

        return view('admin.dashboard', 
                    compact(
                            'emp_typeR', 
                            'emp_typeC',
                            'emp_typeT', 
                            'emp_allActive',
                            'emp_all_type', 
                            'emp_birthday',
                            'emp_leaves_pending', 
                            'emp_leaves_approve', 
                            'emp_leaves_all', 
                            'emp_approved_leave', 
                            'a', 
                            'is_online_now', 
                            'is_offline_now',
                            'setting')
                    );

    }
}

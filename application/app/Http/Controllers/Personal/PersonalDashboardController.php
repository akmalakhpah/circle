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

        $setting = table::settings()->first();

        $id = \Auth::user()->reference;
        $sm = date('m/01/Y');
        $em = date('m/31/Y');

        $profile = table::people()
        ->leftjoin('tbl_company_data', 'tbl_people.id', '=', 'tbl_company_data.reference')
        ->where('tbl_people.id', $id)->first();

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
            $cs = table::schedules()->where([
                ['reference', $id], 
                ['archive', '0']
            ])->first();

            $ps = table::schedules()->where([
                ['reference', $id],
                ['archive', '1'],
            ])->take(4)->get();

            $al = table::leaves()->where([['reference', $id], ['status', 'Approved']])->count();
            $ald = table::leaves()->where([['reference', $id], ['status', 'Approved']])->take(4)->get();
            $pl = table::leaves()->where([['reference', $id], ['status', 'Declined']])->orWhere('status', 'Pending')->count();
            $a = table::attendance()->where('reference', $id)->latest('date')->take(4)->get();

            $la = table::attendance()->where([['reference', $id], ['status_timein', 'Late Arrival']])->whereBetween('date', [$sm, $em])->count();
            $ed = table::attendance()->where([['reference', $id], ['status_timeout', 'Early Departure']])->whereBetween('date', [$sm, $em])->count();
        }

        return view('personal.personal-dashboard', 
            compact(
                    'profile',
                    'emp_typeR', 
                    'emp_typeC',
                    'emp_typeT', 
                    'emp_allActive',
                    'emp_all_type',
                    'emp_birthday',
                    'cs', 'ps', 'al', 'pl', 'ald', 'a', 'la', 'ed',
                    'setting'
                ));
    }
}


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


class SettingsController extends Controller
{
    public function index() {
        if (permission::permitted('settings')=='fail'){ return view('errors.permission-denied'); }
        $data = table::settings()->where('id', 1)->first();

    	return view('admin.settings', compact('data'));
    }

    public function update(Request $request) {
        if (permission::permitted('settings')=='fail'){ return view('errors.permission-denied'); }
        
        $country = $request->country;
        $timezone = $request->timezone;
        $enable_google_login = $request->enable_google_login;
        $enable_attendance = $request->enable_attendance;
        $enable_okr = $request->enable_okr;
        $enable_asana_report = $request->enable_asana_report;
        $clock_comment = $request->clock_comment;
        $iprestriction = $request->iprestriction;
        $a_email = $request->email;
        $a_phone = $request->phone;
        
        if ($country == null && $timezone == null && $a_email == null && $a_phone == null && $clock_comment == null && $iprestriction == null) {
            return redirect('settings');
        }

        if ($timezone == null) {
            return redirect('settings')->with('error', 'Please select your timezone.');
        } else {
            $t = table::settings()->where('id', 1)->value('timezone');
            $path = base_path('.env');
            if(file_exists($path)) {
                file_put_contents($path, str_replace(
                    'APP_TIMEZONE='.$t, 'APP_TIMEZONE='.$timezone, file_get_contents($path)
                ));
            }
        }

        table::settings()
        ->where('id', 1)
        ->update([
                'country' => $country,
                'timezone' => $timezone,
                'admin_email' => $a_email,
                'admin_phone' => $a_phone,
                'enable_google_login' => $enable_google_login,
                'enable_attendance' => $enable_attendance,
                'enable_okr' => $enable_okr,
                'enable_asana_report' => $enable_asana_report,
                'clock_comment' => $clock_comment,
                'iprestriction' => $iprestriction,
        ]);
        
        return redirect('settings')->with('success', 'Settings has been updated. Please try re-login for the new settings to take effect.');
    }
}

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
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;


class AsanaUsersController extends Controller
{
    public function index()
    {
        if (permission::permitted('users')=='fail'){ return view('errors.permission-denied'); }

        $asana_users = table::asana_users()->leftjoin('tbl_people', 'tbl_asana_users.reference', '=', 'tbl_people.id')->select('tbl_asana_users.*', DB::raw("CONCAT(tbl_people.lastname,', ',tbl_people.firstname) AS employee"))->get();
        return view('admin.asana-users', compact('asana_users'));

    }

    public function edit($id) {
        if (permission::permitted('users-edit')=='fail'){ return view('errors.permission-denied'); }
        
        $u = table::asana_users()->leftjoin('tbl_people', 'tbl_asana_users.reference', '=', 'tbl_people.id')->select('tbl_asana_users.*', 'tbl_people.emailaddress')->where('tbl_asana_users.id', $id)->first();
        $employees = table::people()->orderBy('firstname')->get();
        return view('admin.edits.edit-asana-user', compact('u','employees'));
    }

    public function update(Request $request) {
        if (permission::permitted('users-edit')=='fail'){ return view('errors.permission-denied'); }

        $ref = $request->ref;
        $id = $request->id;
        $status = $request->status;

        table::asana_users()->where('id', $id)->update([
            'status' => $status,
            'reference' => $ref,
        ]);

        return redirect('/asana-users')->with('success','User Account has been updated!');       
    }

}

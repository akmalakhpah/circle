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


class FieldsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Company
    |--------------------------------------------------------------------------
    */
    public function company() {
      if (permission::permitted('formdata-company')=='fail'){
		    return view('errors.permission-denied');
        
      }

      $data = table::company()->get();
      return view('admin.fields.company', compact('data'));
    }

    public function addCompany(Request $request)
    {
      if (permission::permitted('formdata-company')=='fail'){
		    return view('errors.permission-denied');
        
      }

      $company = mb_strtoupper($request->company);

      if ($company == null) {
        return redirect('fields/company')->with('error', 'Whoops! Please fill the form completely!');
      }

      table::company()->insert([
        ['company' => $company],
      ]);

      return redirect('fields/company')->with('success','New Company has been saved.');
    }

    public function deleteCompany($id)
    {
      if (permission::permitted('formdata-company')=='fail'){
		    return view('errors.permission-denied');
        
      }

      table::company()->where('id', $id)->delete();

      return redirect('fields/company')->with('success','Deleted!');
    }


    /*
    |--------------------------------------------------------------------------
    | Department
    |--------------------------------------------------------------------------
    */
    public function department() {
      if (permission::permitted('formdata-departments')=='fail'){
		    return view('errors.permission-denied');
        
      }

      $data = table::department()->get();
      $c = table::company()->get();
      return view('admin.fields.department', compact('data','c'));
    }

    public function addDepartment(Request $request)
    {
      if (permission::permitted('formdata-departments')=='fail'){
		    return view('errors.permission-denied');
        
      }

      $department = mb_strtoupper($request->department);
      $comp_code = $request->comp_code;

      if ($department == null || $comp_code == null) {
        return redirect('fields/departments')->with('error', 'Whoops! Please fill the form completely!');
      }

      table::department()->insert([
        [
          'department' => $department,
          'comp_code' => $comp_code
        ],
      ]);

      return redirect('fields/department')->with('success','New Department has been saved.');
    }

    public function deleteDepartment($id)
    {
      if (permission::permitted('formdata-departments')=='fail'){
		    return view('errors.permission-denied');
        
      }

      table::department()->where('id', $id)->delete();

      return redirect('fields/department')->with('success','Deleted!');
    }

    /*
    |--------------------------------------------------------------------------
    | Job Title or Position
    |--------------------------------------------------------------------------
    */
    public function jobtitle() {
      if (permission::permitted('formdata-jobtitle')=='fail'){
		    return view('errors.permission-denied');
      }

      $data = table::jobtitle()->get();
      $d = table::department()->get();
      return view('admin.fields.jobtitle', compact('data', 'd'));
    }

    public function addJobtitle(Request $request)
    {
      if (permission::permitted('formdata-jobtitle')=='fail'){
		    return view('errors.permission-denied');
      }

      $jobtitle = mb_strtoupper($request->jobtitle);
      $dept_code = $request->dept_code;

      if ($jobtitle == null || $dept_code == null) {
        return redirect('/fields/jobtitle')->with('error', 'Whoops! Please fill the form completely!');
      }

      table::jobtitle()->insert([
        [
          'jobtitle' => $jobtitle, 
          'dept_code' => $dept_code
        ],
      ]);

      return redirect('/fields/jobtitle')->with('success','New Job Title has been saved.');
    }

    public function deleteJobtitle($id)
    {
      if (permission::permitted('formdata-jobtitle')=='fail'){
		    return view('errors.permission-denied');
      }

      table::jobtitle()->where('id', $id)->delete();
      return redirect('fields/jobtitle')->with('success','Deleted!');
    }


    /*
    |--------------------------------------------------------------------------
    | Leave Type
    |--------------------------------------------------------------------------
    */
    public function leavetype() {
        if (permission::permitted('formdata-leavetypes')=='fail'){
		    return view('errors.permission-denied');
        }

        $data = table::leavetypes()->get();
        return view('admin.fields.leavetype', compact('data'));
    }

    public function addLeavetype(Request $request)
    {
      if (permission::permitted('formdata-leavetypes')=='fail'){
		    return view('errors.permission-denied');
      }

      $leavetype = mb_strtoupper($request->leavetype);
      $limit = $request->limit;
      $percalendar = $request->percalendar;

      if ($leavetype == null || $limit == null || $percalendar == null) {
        return redirect('/fields/leavetype')->with('error', 'Whoops! Please fill the form completely!');
      }

      table::leavetypes()->insert([
        ['leavetype' => $leavetype,'limit' => $limit, 'percalendar' => $percalendar]
      ]);

      return redirect('/fields/leavetype')->with('success','New Leave Type has been saved.');
    }

    public function deleteLeavetype($id)
    {
      if (permission::permitted('formdata-leavetypes')=='fail'){
		    return view('errors.permission-denied');
      }
      
      table::leavetypes()->where('id', $id)->delete();

      return redirect('fields/leavetype')->with('success','Deleted!');
    }


    /*
    |--------------------------------------------------------------------------
    | Leave Groups
    |--------------------------------------------------------------------------
    */
    public function leaveGroups() {
      if (permission::permitted('formdata-leavetypes')=='fail'){
		    return view('errors.permission-denied');
      }

      $lt = table::leavetypes()->get();
      $lg = table::leavegroup()->get();

      return view('admin.fields.leave-groups', compact('lt', 'lg'));
    }

    public function addLeaveGroups(Request $request) {
      if (permission::permitted('formdata-leavetypes')=='fail'){
		    return view('errors.permission-denied');
      }

      $leavegroup = strtoupper($request->leavegroup); 
      $description = strtoupper($request->description);
      $status = $request->status;
      $leaveprivileges = implode(',', $request->leaveprivileges);

      if($request->leavegroup == null || $request->description == null || $request->status == null) {
        return redirect('fields/leavetype/leave-groups')->with('error', 'Whoops! Please fill the form completely!');
      }

      table::leavegroup()->insert([
        ["leavegroup" => $leavegroup, "description" => $description, "leaveprivileges" => $leaveprivileges, "status" => $status]
      ]);

      return redirect('fields/leavetype/leave-groups')->with('success', 'New Leave Group has been saved!');
    }

    public function editLeaveGroups($id) {
      if (permission::permitted('formdata-leavetypes')=='fail'){
		    return view('errors.permission-denied');
      }

      $lt = table::leavetypes()->get();
      $lg = table::leavegroup()->where("id", $id)->first();
      return view('admin.edits.edit-leavegroups', compact('lg', 'lt'));
    }

    public function updateLeaveGroups(Request $request) {
      if (permission::permitted('formdata-leavetypes')=='fail'){
		    return view('errors.permission-denied');
      }

      $leavegroup = strtoupper($request->leavegroup); 
      $description = strtoupper($request->description);
      $status = $request->status;
      $leaveprivileges = implode(',', $request->leaveprivileges);
      $id = $request->id;

      if($request->leavegroup == null || $request->description == null || $request->status == null || $id == null) {
        return redirect('fields/leavetype/leave-groups')->with('error', 'Whoops! Please fill the form completely!');
      }

      table::leavegroup()->where('id', $id)->update([
          "leavegroup" => $leavegroup,
          "description" => $description,
          "leaveprivileges" => $leaveprivileges,
          "status" => $status
      ]);

      return redirect('fields/leavetype/leave-groups')->with('success', 'Leave group has been update!');
    }

    public function deleteLeaveGroups($id) {
      if (permission::permitted('formdata-leavetypes')=='fail'){
		    return view('errors.permission-denied');
      }

      table::leavegroup()->where('id', $id)->delete();

      return redirect('fields/leavetype/leave-groups')->with('success', 'Deleted!');
    }
} 
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
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;


class ImportsController extends Controller
{

    function csvToArray($filename) 
    {
    	if( !file_exists($filename) || !is_readable($filename) ) 
    	{
    		return false;
    	}

    	$header = null;
    	if (($handle = fopen($filename, 'r')) !== false) 
    	{
    		while(($row = fgetcsv($handle, 1000, ',')) !== false) 
    		{
    			if (!$header) {
    				$header = $row;
    			} else {
    				$data[] = $row;
    			}
    		}
    		fclose($handle);
    	} 
    	return $data;
    }

	function importCompany(Request $request) 
	{
		$uploadedfile = $request->file('csv');
		if ($uploadedfile != null) {
			$name = $request->file('csv')->getClientOriginalName();
			$destinationPath = storage_path() . '/app/';
			$uploadedfile->move($destinationPath, $name);
	
			$file = storage_path('app/' . $name);
			$array = $this->csvToArray($file);
			
			foreach ($array as $value) {
				table::company()->insert([
					[ 'id' => $value[0], 'company' => $value[1] ],
				]);
			}

			return redirect('/fields/company');
		} else {
			return redirect('/fields/company')->with('error', 'Whoops!, Please upload a csv file.');
		}
	}

	function importDepartment(Request $request) 
	{
		$uploadedfile = $request->file('csv');
		if ($uploadedfile != null) {
			$name = $request->file('csv')->getClientOriginalName();
			$destinationPath = storage_path() . '/app/';
			$uploadedfile->move($destinationPath, $name);

			$file = storage_path('app/' . $name);
			$array = $this->csvToArray($file);
			
			foreach ($array as $value) {
				table::department()->insert([
					[ 'id' => $value[0], 'department' => $value[1] ],
				]);
			}

			return redirect('/fields/department');
		} else {
			return redirect('/fields/department')->with('error', 'Whoops!, Please upload a csv file.');
		}
	}
	
	function importJobtitle(Request $request) 
	{
		$uploadedfile = $request->file('csv');
		if ($uploadedfile != null) { 
			$name = $request->file('csv')->getClientOriginalName();
			$destinationPath = storage_path() . '/app/';
			$uploadedfile->move($destinationPath, $name);
	
			$file = storage_path('app/' . $name);
			$array = $this->csvToArray($file);
			
			foreach ($array as $value) {
				table::jobtitle()->insert([
					[ 'id' => $value[0], 'jobtitle' => $value[1], 'dept_Code' => $value[2] ],
				]);
			}
	
			return redirect('/fields/jobtitle');
		} else {
			return redirect('/fields/jobtitle')->with('error', 'Whoops!, Please upload a csv file.');
		}

	}

	function importLeavetypes(Request $request) 
	{
		$uploadedfile = $request->file('csv');
		if($uploadedfile != null) {
			$name = $request->file('csv')->getClientOriginalName();
			$destinationPath = storage_path() . '/app/';
			$uploadedfile->move($destinationPath, $name);

			$file = storage_path('app/' . $name);
			$array = $this->csvToArray($file);
			
			foreach ($array as $value) {
				table::leavetypes()->insert([
					[ 'id' => $value[0], 'leavetype' => $value[1], 'limit' => $value[2], 'percalendar' => $value[3] ],
				]);
			}

			return redirect('/fields/leavetypes');
		} else {
			return redirect('/fields/leavetypes')->with('error', 'Whoops!, Please upload a csv file.');
		}
	}
	
}

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
use DateTimeZone;
use DateTime;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class PersonalReportsController extends Controller
{
    public function index() {
        $setting = table::settings()->first();

    	return view('personal.personal-reports', compact('setting'));
    }

	public function empList() {
		$today = date('M, d Y');
		table::reportviews()->where('report_id', 1)->update(['last_viewed' => $today]);

		$empList = table::people()->join('tbl_company_data', 'tbl_people.id', '=', 'tbl_company_data.reference')->get();
		
		return view('personal.reports.report-employee-list', compact('empList'));
	}

	public function empAtten() {
		$today = date('M, d Y');
		table::reportviews()->where('report_id', 2)->update(array('last_viewed' => $today));

		$empAtten = table::attendance()->get();
		$employee = table::people()->join('tbl_company_data', 'tbl_people.id', '=', 'tbl_company_data.reference')->where('tbl_people.employmentstatus', 'Active')->get();

		return view('personal.reports.report-employee-attendance', compact('empAtten', 'employee'));
	}

	public function empLeaves() {
		$today = date('M, d Y');
		table::reportviews()->where('report_id', 3)->update(array('last_viewed' => $today));

		$employee = table::people()->join('tbl_company_data', 'tbl_people.id', '=', 'tbl_company_data.reference')->where('tbl_people.employmentstatus', 'Active')->get();
		$empLeaves = table::leaves()->get();
		return view('personal.reports.report-employee-leaves', compact('empLeaves', 'employee'));
	}

	public function empSched() {
		$today = date('M, d Y');
		table::reportviews()->where('report_id', 4)->update(array('last_viewed' => $today));

		$empSched = table::schedules()->orderBy('archive', 'ASC')->get();
		$employee = table::people()->join('tbl_company_data', 'tbl_people.id', '=', 'tbl_company_data.reference')->where('tbl_people.employmentstatus', 'Active')->get();
		return view('personal.reports.report-employee-schedule', compact('empSched', 'employee'));
	}

	public function orgProfile() {
		$today = date('M, d Y');
		table::reportviews()->where('report_id', 5)->update(array('last_viewed' => $today));

		$ed = table::people()->join('tbl_company_data', 'tbl_people.id', '=', 'tbl_company_data.reference')->where('tbl_people.employmentstatus', 'Active')->orderBy('tbl_company_data.company')->orderBy('tbl_company_data.department')->get();
		
		// age count 0-19 bracket
		$age_18_24 = table::people()->where([['age', '>=', '18'], ['age', '<=', '24']])->count();
		$age_25_31 = table::people()->where([['age', '>=', '25'], ['age', '<=', '31']])->count();
		$age_32_38 = table::people()->where([['age', '>=', '32'], ['age', '<=', '38']])->count();
		$age_39_45 = table::people()->where([['age', '>=', '39'], ['age', '<=', '45']])->count();
		$age_46_100 = table::people()->where('age', '>=', '46')->count();
		
		// if null val 0
		if($age_18_24 == null) {$age_18_24 = 0;};
		if($age_25_31 == null) {$age_25_31 = 0;};
		if($age_32_38 == null) {$age_32_38 = 0;};
		if($age_39_45 == null) {$age_39_45 = 0;};
		if($age_46_100 == null) {$age_46_100 = 0;};	

		// chart age group
		$age_group = $age_18_24.','.$age_25_31.','.$age_32_38.','.$age_39_45.','.$age_46_100;

		// chart company
		foreach ($ed as $c) { $comp[] = $c->company; $dcc = array_count_values($comp); }
		$cc = implode($dcc, ', ') . ',';

		// chart parent
		foreach ($ed as $d) { $dept[] = $d->department; $dpc = array_count_values($dept); }
		$dc = implode($dpc, ', ') . ',';

		// chart gender
		foreach ($ed as $g) { $gender[] = $g->gender; $dgc = array_count_values($gender); }
		$gc = implode($dgc, ', ') . ',';

		// chart civil status
		foreach ($ed as $cs) { $civilstatus[] = $cs->civilstatus; $csc = array_count_values($civilstatus); }
		$cg = implode($csc, ', ') . ',';

		// chart year hired
		// $tz = ini_get('date.timezone');
        // $dtz = new DateTimeZone($tz);
		foreach ($ed as $yearhired) {
			$year[] = date("Y", strtotime($yearhired->startdate));
			asort($year); 
			$yhc = array_count_values($year);
		}
		$yc = implode($yhc, ', ') . ',';
		
		$orgProfile = table::companydata()->get();

		return view('personal.reports.report-organization-profile', compact('orgProfile', 'age_group', 'gc', 'dgc', 'cg', 'csc', 'yc', 'yhc', 'dc', 'dpc', 'dcc', 'cc'));
	}

	public function empBday() {
		$today = date('M, d Y');
		table::reportviews()->where('report_id', 7)->update(['last_viewed' => $today]);

		$empBday = table::people()->join('tbl_company_data', 'tbl_people.id', '=', 'tbl_company_data.reference')->get();
		return view('personal.reports.report-employee-birthdays', compact('empBday'));
	}

	public function userAccs() {
		$today = date('M, d Y');
		table::reportviews()->where('report_id', 6)->update(['last_viewed' => $today]);

		$userAccs = table::users()->get();
		return view('personal.reports.report-user-accounts', compact('userAccs'));
	}

	public function getEmpAtten(Request $request) {
		$id = $request->id;
		$datefrom = $request->datefrom;
		$dateto = $request->dateto;
		
		if ($id == null AND $datefrom == null AND $dateto == null) {
			$data = table::attendance()->select('idno', 'date', 'employee', 'timein', 'timeout', 'totalhours')->get();
			return response()->json($data);
		}

		if($id !== null AND $datefrom == null AND $dateto == null ) {
		 	$data = table::attendance()->where('idno', $id)->select('idno', 'date', 'employee', 'timein', 'timeout', 'totalhours')->get();
			return response()->json($data);

		} elseif ($id !== null AND $datefrom !== null AND $dateto !== null) {
			$data = table::attendance()->where('idno', $id)->whereBetween('date', [$datefrom, $dateto])->select('idno', 'date', 'employee', 'timein', 'timeout', 'totalhours')->get();
			return response()->json($data);

		} elseif ($id == null AND $datefrom !== null AND $dateto !== null) {
			$data = table::attendance()->whereBetween('date', [$datefrom, $dateto])->select('idno', 'date', 'employee', 'timein', 'timeout', 'totalhours')->get();
			return response()->json($data);
		} 
	}

	public function getEmpLeav(Request $request) {
		$id = $request->id;
		$datefrom = $request->datefrom;
		$dateto = $request->dateto;

		if ($id == null AND $datefrom == null AND $dateto == null) {
			$data = table::leaves()->select('idno', 'employee', 'type', 'leavefrom', 'leaveto', 'status', 'reason')->get();
			return response()->json($data);
		}

		if($id !== null AND $datefrom == null AND $dateto == null ) {
			$data = table::leaves()->where('idno', $id)->select('idno', 'employee', 'type', 'leavefrom', 'leaveto', 'status', 'reason')->get();
			return response()->json($data);

		} elseif ($id !== null AND $datefrom !== null AND $dateto !== null) {
			$data = table::leaves()->where('idno', $id)->whereBetween('leavefrom', [$datefrom, $dateto])->select('idno', 'employee', 'type', 'leavefrom', 'leaveto', 'status', 'reason')->get();
			return response()->json($data);

		} elseif ($id == null AND $datefrom !== null AND $dateto !== null) {
			$data = table::leaves()->whereBetween('leavefrom', [$datefrom, $dateto])->select('idno', 'employee', 'type', 'leavefrom', 'leaveto', 'status', 'reason')->get();
			return response()->json($data);
		} 
	}

	public function getEmpSched(Request $request) {
		$id = $request->id;
		
		if ($id == null) {
			$data = table::schedules()->select('reference', 'employee', 'intime', 'outime', 'datefrom', 'dateto', 'hours', 'restday', 'archive')->orderBy('archive', 'ASC')->get();
			return response()->json($data);
		}

		if($id !== null) {
		 	$data = table::schedules()->where('idno', $id)->select('reference', 'employee', 'intime', 'outime', 'datefrom', 'dateto', 'hours', 'restday', 'archive')->orderBy('archive', 'ASC')->get();
			return response()->json($data);
		} 
	}

	public function asanaTask(string $profile = "my", Request $request) {
		$id = \Auth::user()->reference;
		$parent_members_gid = array();
		$user_gid = array();
		$others = null;
 		$mine = $id;
 		$other = null;

 		if(isset($request->other)) {
			$other = $request->other;
 		}

 		if(\Auth::user()->role_id == 2){
 			$others = array();
 		} else {
 			$other = null;
 		}

		if($profile == "my"){

			if(isset($other)){ // get other user u_id
				$mine = $other;
				$u_gid = table::asana_users()->where('reference', $other)->first();
			} else { // get own user u_id
				$mine = $id;
				$u_gid = table::asana_users()->where('reference', $id)->first();
			}
			if(isset($u_gid))
				$user_gid[] = $u_gid->gid;
		} else {

			if(isset($other)){ // get other department id
				$dept = table::department()->where("id", $other)->first();
				if(isset($dept))
					$department = $dept->department;
				$mine = $other;
			} else { // get own department id
		        $department = table::companydata()->where('reference',$id)->first();
		        if(isset($department))
		        {
		        	$department = $department->department;
		        	$dept = table::department()->where("department", $department)->first();
		        	if(isset($dept))
		        		$mine = $dept->id;
		        }
			}

	        $department_members = table::companydata()->select('gid')->leftjoin('tbl_people','tbl_company_data.reference','=','tbl_people.id')->leftjoin('tbl_asana_users','tbl_asana_users.reference','=','tbl_people.id')->where('department',$department)->where('tbl_people.employmentstatus', 'Active')->get()->toArray();
	        foreach ($department_members as $value) {
	        	if(isset($value->gid)){
	        		//if($value->gid != $user_gid)
	        			$user_gid[] = $value->gid;
	        	}
	        }

		}

		if($profile == "my"){
			if(isset($other)){ // get other user's parent
				$parent = table::companydata()->where('reference',$other)->first();
			} else { // get own user's parent
				$parent = table::companydata()->where('reference',$id)->first();
			}
	        
	        if(isset($parent))
	        	$parent = $parent->department;

	        if(\Auth::user()->role_id == 2) {
	        	if(isset($u_gid)){
					$others[$u_gid->reference] = strtoupper($u_gid->name);
				} else {
					$others[\Auth::user()->reference] = \Auth::user()->name;
				}
	        	
	        }

	        $parent_members = table::companydata()->select('gid','tbl_company_data.reference as value', DB::raw('concat(tbl_people.firstname, " ", tbl_people.lastname) as name'))->leftjoin('tbl_people','tbl_company_data.reference','=','tbl_people.id')->leftjoin('tbl_asana_users','tbl_asana_users.reference','=','tbl_people.id')->where('department',$parent)->where('tbl_people.employmentstatus', 'Active')->whereNotIn('gid',$user_gid)->get()->toArray();
	        foreach ($parent_members as $value) {
	        	if(isset($value->gid)){
	        		//if($value->gid != $user_gid)
	        		if(\Auth::user()->role_id == 2)
	        			$others[$value->value] = $value->name;
	        		$parent_members_gid[] = $value->gid;
	        	}
	        }

        	$parent_members = count($parent_members);
	    } else {
	        $parent = table::companydata()->where('reference',$id)->first();
	        if(isset($parent)){

				if(isset($other)){ // get other department's parent
					$dept = table::department()->select('tbl_form_department.department as department','tbl_form_company.company as company')->leftjoin('tbl_form_company','tbl_form_company.id','=','tbl_form_department.comp_code')->where("tbl_form_department.id", $other)->first();
	        		$parent = $dept->company;
					$dept = $dept->department;
				} else { // get own department's parent
					$dept = $parent->department;
	        		$parent = $parent->company;
				}

	        	//get own dept
	        	$dept = table::department()->select('tbl_form_department.id as value','tbl_form_department.department as name')->where("tbl_form_department.department", $dept)->get()->toArray();
	        	foreach ($dept as $value) {
		        	if(\Auth::user()->role_id == 2)
		        		$others[$value->value] = $value->name;
		        }

		        //get other deptx
	        	$dept = table::department()->select('tbl_form_department.id as value','tbl_form_department.department as name')->leftjoin('tbl_form_company','tbl_form_company.id','=','tbl_form_department.comp_code')->where("tbl_form_company.company", $parent)->get()->toArray();
	        	foreach ($dept as $value) {
		        	if(\Auth::user()->role_id == 2)
		        		$others[$value->value] = $value->name;
		        }
	        }

	        $parent_members = table::companydata()->select('gid')->leftjoin('tbl_people','tbl_company_data.reference','=','tbl_people.id')->leftjoin('tbl_asana_users','tbl_asana_users.reference','=','tbl_people.id')->where('company',$parent)->where('tbl_people.employmentstatus', 'Active')->whereNotIn('gid',$user_gid)->get()->toArray();
	        foreach ($parent_members as $value) {
	        	if(isset($value->gid)){
	        		//if($value->gid != $user_gid)
	        			$parent_members_gid[] = $value->gid;
	        	}
	        }

        	$parent_members = table::companydata()->select('department')->leftjoin('tbl_people','tbl_company_data.reference','=','tbl_people.id')->leftjoin('tbl_asana_users','tbl_asana_users.reference','=','tbl_people.id')->where('company',$parent)->where('tbl_people.employmentstatus', 'Active')->whereNotIn('gid',$user_gid)->groupBy('department')->count();
	    }

		$type = 'week';
		if(isset($request->type))
			$type = $request->type;


        switch($type){
            case 'day':
            	$datefilter = "(YEAR(subdate(current_date, 1)) = YEAR(completed_at) AND MONTH(subdate(current_date, 1)) = MONTH(completed_at) AND DAY(subdate(current_date, 1)) = DAY(completed_at))";
            	$dateallfilter = "(completed_at > DATE_SUB(DATE_SUB(curdate(), INTERVAL day(curdate())-1 DAY), INTERVAL 1 MONTH))";
            	$datedisplay = "Y-m-d";
                break;

            case 'week':
            	$datefilter = "(YEAR(curdate()) = YEAR(completed_at) AND WEEK(curdate()) = WEEK(completed_at))";
            	$dateallfilter = "(completed_at > DATE_SUB(DATE_SUB(curdate(), INTERVAL day(curdate())-1 DAY), INTERVAL 2 MONTH))";
            	$datedisplay = "Y-W";
                break;

            case 'month':
            	$datefilter = "(YEAR(curdate()) = YEAR(completed_at) AND MONTH(curdate()) = MONTH(completed_at))";
            	$dateallfilter = "(completed_at > DATE_SUB(DATE_SUB(curdate(), INTERVAL day(curdate())-1 DAY), INTERVAL 12 MONTH))";
            	$datedisplay = "Y-m";
                break;

            case 'year':
            	$datefilter = "(YEAR(curdate()) = YEAR(completed_at))";
            	$dateallfilter = "(YEAR(curdate()) >= YEAR(completed_at))";
            	$datedisplay = "Y";
                break;

            default:
            	$datefilter = "(YEAR(curdate()) = YEAR(completed_at) AND MONTH(curdate()) = MONTH(completed_at) AND DAY(curdate()) = DAY(completed_at))";
            	$dateallfilter = "(completed_at > DATE_SUB(DATE_SUB(curdate(), INTERVAL day(curdate())-1 DAY), INTERVAL 1 MONTH))";
            	$datedisplay = "Y-m-d";
                break;
        }

		$today = date('M, d Y');
		//table::reportviews()->where('report_id', 5)->update(array('last_viewed' => $today));

		//task_ontime_overdue
		$task_ontime_overdue = table::asana_tasks()
			->select(DB::raw("IF((completed_at < due_on OR due_on IS NULL), 'On-time', 'Overdue' ) as status"))
			->whereIn('user_gid',$user_gid)
			->whereRaw($datefilter)
			->orderBy('status')
			->get();

		foreach ($task_ontime_overdue as $g) { $status[] = $g->status; $toodata = array_count_values($status); }
		if(isset($toodata))
			$too = implode($toodata, ', ') . ',';
		else
			$too = '';

		// completed task
		$task_completed = table::asana_tasks()->select('completed_at')->whereIn('user_gid',$user_gid)
		   	->whereRaw('((completed_at < due_on) OR (due_on IS NULL))')
		   	->whereNotNull('completed_at')
			->whereRaw($dateallfilter)
		   	->get();
		$task_completed_d = table::asana_tasks()->select('completed_at')->whereIn('user_gid',$parent_members_gid)
		   	->whereRaw('((completed_at < due_on) OR (due_on IS NULL))')
		   	->whereNotNull('completed_at')
			->whereRaw($dateallfilter)
		   	->get();
		$task_overdue = table::asana_tasks()->select('completed_at')->whereIn('user_gid',$user_gid)
		   	->whereRaw('(((completed_at > due_on) AND (due_on IS NOT NULL)))')
		   	->whereNotNull('completed_at')
			->whereRaw($dateallfilter)
		   	->get();
		$task_overdue_d = table::asana_tasks()->select('completed_at')->whereIn('user_gid',$parent_members_gid)
		   	->whereRaw('(((completed_at > due_on) AND (due_on IS NOT NULL)))')
		   	->whereNotNull('completed_at')
			->whereRaw($dateallfilter)
		   	->get();

		foreach ($task_completed as $task) {
			$completed_p[] = " ". strval(date($datedisplay, strtotime($task->completed_at)));
		}
		foreach ($task_overdue as $task) {
			$overdue_p[] = " ". strval(date($datedisplay, strtotime($task->completed_at)));
		}
		foreach ($task_completed_d as $task) {
			$completed_d[] = " ". strval(date($datedisplay, strtotime($task->completed_at)));
		}
		foreach ($task_overdue_d as $task) {
			$overdue_d[] = " ". strval(date($datedisplay, strtotime($task->completed_at)));
		}

		$ctp = array();
		$ctd = array();
		$cop = array();
		$cod = array();

		if(isset($completed_p)){
			asort($completed_p); 
			$ctp = array_count_values($completed_p); 
		}
		if(isset($completed_d)){
			asort($completed_d); 
			$ctd = array_count_values($completed_d);
		}
		if(isset($overdue_p)){
			asort($overdue_p); 
			$cop = array_count_values($overdue_p);
		}
		if(isset($overdue_d)){
			asort($overdue_d);
			$cod = array_count_values($overdue_d);
		}


		$ctpdata = array();
		$ctddata = array();
		$copdata = array();
		$coddata = array();
		$ctpdata_avg = 0;
		$ctddata_avg = 0;
		$copdata_avg = 0;
		$coddata_avg = 0;
		$ct = array_merge($ctp, $ctd, $cop, $cod);
		ksort($ct);
		foreach($ct as $key => $value){
			if(isset($ctp[$key])){
				$ctpdata[] = $ctp[$key];
				$ctpdata_avg = $ctpdata_avg + $ctp[$key];
			}
			else{
				$ctpdata[] = 0;
			}

			if(isset($ctd[$key])){
				$ctddata[] = $ctd[$key]/$parent_members;
				$ctddata_avg = $ctddata_avg + ($ctd[$key]/$parent_members);
			}
			else{
				$ctddata[] = 0;
			}
		}
		$ctpdata = implode($ctpdata, ', ') . ',';
		$ctddata = implode($ctddata, ', ') . ',';
		foreach($ct as $key => $value){
			if(isset($cop[$key])){
				$copdata[] = $cop[$key];
				$copdata_avg = $copdata_avg + $cop[$key];
			}
			else{
				$copdata[] = 0;
			}

			if(isset($cod[$key])){
				$coddata[] = $cod[$key]/$parent_members;
				$coddata_avg = $coddata_avg + ($cod[$key]/$parent_members);
			}
			else{
				$coddata[] = 0;
			}
		}
		$copdata = implode($copdata, ', ') . ',';
		$coddata = implode($coddata, ', ') . ',';

		$ctpdata_avg = $ctpdata_avg / count($ct);
		$ctddata_avg = $ctddata_avg / count($ct);
		$copdata_avg = $copdata_avg / count($ct);
		$coddata_avg = $coddata_avg / count($ct);

		$orgProfile = table::companydata()->get();

		// overdue period
		$days_1_2  = table::asana_tasks()->whereIn('user_gid',$user_gid)
						->whereRaw($datefilter)
						->whereRaw('(DATEDIFF(completed_at,due_on) >= 1 AND DATEDIFF(completed_at,due_on) <= 2)')
						->count();
		$days_3_5  = table::asana_tasks()->whereIn('user_gid',$user_gid)
						->whereRaw($datefilter)
						->whereRaw('(DATEDIFF(completed_at,due_on) >= 3 AND DATEDIFF(completed_at,due_on) <= 5)')
						->count();
		$days_6_7  = table::asana_tasks()->whereIn('user_gid',$user_gid)
						->whereRaw($datefilter)
						->whereRaw('(DATEDIFF(completed_at,due_on) >= 6 AND DATEDIFF(completed_at,due_on) <= 7)')
						->count();
		$days_8_14 = table::asana_tasks()->whereIn('user_gid',$user_gid)
						->whereRaw($datefilter)
						->whereRaw('(DATEDIFF(completed_at,due_on) >= 8 AND DATEDIFF(completed_at,due_on) <= 14)')
						->count();
		$more_15   = table::asana_tasks()->whereIn('user_gid',$user_gid)
						->whereRaw($datefilter)
						->whereRaw('DATEDIFF(completed_at,due_on) >= 13')
						->count();
		
		if($days_1_2 == null) {$days_1_2 = 0;};
		if($days_3_5 == null) {$days_3_5 = 0;};
		if($days_6_7 == null) {$days_6_7 = 0;};
		if($days_8_14 == null) {$days_8_14 = 0;};
		if($more_15 == null) {$more_15 = 0;};	
		$overdue_period = $days_1_2.','.$days_3_5.','.$days_6_7.','.$days_8_14.','.$more_15;

        if($profile=='department'){
            $personal_name = "My Department";
            $parent_name = "Other Departments";
            if(isset($parent))
				$parent = 'Other departments in my company ('.ucwords(strtolower($parent)).')';
        }
        else{ 
        	$personal_name = "My";
            $parent_name = "My Teammates";
            if(isset($parent))
				$parent = 'Other staff in my department ('.ucwords(strtolower($parent)).')';
        }

		return view('personal.reports.report-asana-task', 
			compact(
				'others', 'mine',
				'orgProfile', 'gc', 'dgc', 'cg', 'csc',
		 		'ct','ctpdata','ctddata','ctpdata_avg','ctddata_avg',
		 		'co','copdata','coddata','copdata_avg','coddata_avg',
		 		'toodata','too',
		 		'overdue_period',
		 		'type', 'parent', 'profile', 'personal_name', 'parent_name'
		 	));
	}
}

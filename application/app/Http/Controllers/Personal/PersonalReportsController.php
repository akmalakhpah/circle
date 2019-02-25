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

		// chart department
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

	public function asanaTask(Request $request) {
		$id = \Auth::user()->reference;

		$user_gid = table::asana_users()->where('reference', $id)->first();
		if(isset($user_gid))
			$user_gid = $user_gid->gid;

        $department = table::companydata()->where('reference',$id)->first();
        if(isset($department))
        	$department = $department->department;

        $department_members = table::companydata()->select('gid')->leftjoin('tbl_people','tbl_company_data.reference','=','tbl_people.id')->leftjoin('tbl_asana_users','tbl_asana_users.reference','=','tbl_people.id')->where('department',$department)->where('tbl_people.employmentstatus', 'Active')->get()->toArray();
        $department_members_gid = array();
        foreach ($department_members as $value) {
        	if(isset($value->gid)){
        		if($value->gid != $user_gid)
        			$department_members_gid[] = $value->gid;
        	}
        }
        $department_members = count($department_members);

		$type = 'week';
		if(isset($request->type))
			$type = $request->type;


        switch($type){
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
			->where('user_gid',$user_gid)
			->whereRaw($datefilter)
			->orderBy('status')
			->get();

		foreach ($task_ontime_overdue as $g) { $status[] = $g->status; $toodata = array_count_values($status); }
		if(isset($toodata))
			$too = implode($toodata, ', ') . ',';
		else
			$too = '';

		// completed task
		$task_completed = table::asana_tasks()->select('completed_at')->where('user_gid',$user_gid)
		   	->whereRaw('((completed_at < due_on) OR (due_on IS NULL))')
		   	->whereNotNull('completed_at')
			->whereRaw($dateallfilter)
		   	->get();
		$task_completed_d = table::asana_tasks()->select('completed_at')->whereIn('user_gid',$department_members_gid)
		   	->whereRaw('((completed_at < due_on) OR (due_on IS NULL))')
		   	->whereNotNull('completed_at')
			->whereRaw($dateallfilter)
		   	->get();
		$task_overdue = table::asana_tasks()->select('completed_at')->where('user_gid',$user_gid)
		   	->whereRaw('(((completed_at > due_on) AND (due_on IS NOT NULL)))')
		   	->whereNotNull('completed_at')
			->whereRaw($dateallfilter)
		   	->get();
		$task_overdue_d = table::asana_tasks()->select('completed_at')->whereIn('user_gid',$department_members_gid)
		   	->whereRaw('(((completed_at > due_on) AND (due_on IS NOT NULL)))')
		   	->whereNotNull('completed_at')
			->whereRaw($dateallfilter)
		   	->get();

		//dd($task_completed_d);
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
		$ctddata_avg = array();
		$copdata = array();
		$coddata_avg = array();
		$ct = array_merge($ctp, $ctd, $cop, $cod);
		ksort($ct);
		foreach($ct as $key => $value){
			if(isset($ctp[$key]))
				$ctpdata[] = $ctp[$key];
			else
				$ctpdata[] = 0;

			if(isset($ctd[$key]))
				$ctddata_avg[] = $ctd[$key]/$department_members;
			else
				$ctddata_avg[] = 0;
		}
		$ctpdata = implode($ctpdata, ', ') . ',';
		$ctddata_avg = implode($ctddata_avg, ', ') . ',';
		foreach($ct as $key => $value){
			if(isset($cop[$key]))
				$copdata[] = $cop[$key];
			else
				$copdata[] = 0;

			if(isset($cod[$key]))
				$coddata_avg[] = $cod[$key]/$department_members;
			else
				$coddata_avg[] = 0;
		}
		$copdata = implode($copdata, ', ') . ',';
		$coddata_avg = implode($coddata_avg, ', ') . ',';

		$orgProfile = table::companydata()->get();

		// overdue period
		$days_1_2  = table::asana_tasks()->where('user_gid',$user_gid)
						->whereRaw($datefilter)
						->whereRaw('(DATEDIFF(completed_at,due_on) >= 1 AND DATEDIFF(completed_at,due_on) <= 2)')
						->count();
		$days_3_5  = table::asana_tasks()->where('user_gid',$user_gid)
						->whereRaw($datefilter)
						->whereRaw('(DATEDIFF(completed_at,due_on) >= 3 AND DATEDIFF(completed_at,due_on) <= 5)')
						->count();
		$days_6_7  = table::asana_tasks()->where('user_gid',$user_gid)
						->whereRaw($datefilter)
						->whereRaw('(DATEDIFF(completed_at,due_on) >= 6 AND DATEDIFF(completed_at,due_on) <= 7)')
						->count();
		$days_8_14 = table::asana_tasks()->where('user_gid',$user_gid)
						->whereRaw($datefilter)
						->whereRaw('(DATEDIFF(completed_at,due_on) >= 8 AND DATEDIFF(completed_at,due_on) <= 14)')
						->count();
		$more_15   = table::asana_tasks()->where('user_gid',$user_gid)
						->whereRaw($datefilter)
						->whereRaw('DATEDIFF(completed_at,due_on) >= 13')
						->count();
		
		if($days_1_2 == null) {$days_1_2 = 0;};
		if($days_3_5 == null) {$days_3_5 = 0;};
		if($days_6_7 == null) {$days_6_7 = 0;};
		if($days_8_14 == null) {$days_8_14 = 0;};
		if($more_15 == null) {$more_15 = 0;};	
		$overdue_period = $days_1_2.','.$days_3_5.','.$days_6_7.','.$days_8_14.','.$more_15;

		if(isset($department))
			$department = '(Department '.ucwords(strtolower($department)).')';

		return view('personal.reports.report-asana-task', 
			compact(
				'orgProfile', 'gc', 'dgc', 'cg', 'csc',
		 		'ct','ctpdata','ctddata_avg',
		 		'co','copdata','coddata_avg',
		 		'toodata','too',
		 		'overdue_period',
		 		'type', 'department'
		 	));
	}
}

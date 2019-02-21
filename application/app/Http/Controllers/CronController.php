<?php
/*
* My Circle: Performance Management System
* Email: circle@aidan.my
* Version: 1.0
* Author: Akmal Akhpah
* Copyright 2019 Aidan Technologies
* Website: https://github.com/akmalakhpah/circle
*/
namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Asana\Client;

class CronController extends Controller
{
    public function asana($type,$key){

        //get Cron job key
        if($key !== config('app.cron_key')){
            return response()->json([
                "error" => "Whoops! Please use valid key to call the Cron job"
            ]);            
        }

        //get Asana personal access token
        $token = config('app.asana_personal_access_token');
        if(!isset($token)){
            return response()->json([
                "error" => "Whoops! Missing Asana personal access token"
            ]);          
        }
            
        //connect to Asana API
        $client = Client::accessToken($token);  
        if(!isset($client)){
            return response()->json([
                "error" => "Whoops! Invalid Asana personal access token"
            ]);          
        }     

        //get all workspaces gid
        $asana_workspaces = table::settings()->value('asana_workspaces');
        $asana_workspaces_gid = array();
        if(isset($asana_workspaces)){
            $w = explode(",", $asana_workspaces);
            
            $workspaces = $client->workspaces->findAll();
            foreach ($workspaces as $workspace) {
                foreach ($w as $workspace_name){
                    if($workspace_name === $workspace->name){
                        $asana_workspaces_gid[] = $workspace->id;
                    }
                }
            }
        }

        $data = array();
        switch ($type) {

            case "users":
                $total_import = 0;
                foreach ($asana_workspaces_gid as $ws_gid){
                    $users = $client->users->findByWorkspace($ws_gid,array(),array('opt_fields' => 'id,name,email'));
                    foreach ($users as $user) {

                        $asana_user = table::asana_users()->where("gid",$user->id)->count();

                        if($asana_user < 1){
                            $path = sprintf("/users/%s/user_task_list", $user->id);
                            $user_task_list = $client->get($path, array('workspace' => $ws_gid));
                            $user_task_list_gid = null;

                            if(isset($user_task_list))
                                $user_task_list_gid = $user_task_list->id;

                            $asana_user = table::asana_users()->updateOrInsert(
                                ['gid' => $user->id],
                                [   'email' => $user->email, 
                                    'name' => $user->name,
                                    'user_task_list_gid' => $user_task_list_gid,
                                    'workspace_gid' => $ws_gid,
                                ]
                            );
                        } else {
                            $asana_user = table::asana_users()->updateOrInsert(
                                ['gid' => $user->id],
                                [   'email' => $user->email, 
                                    'name' => $user->name,
                                    'workspace_gid' => $ws_gid,
                                ]
                            );
                        }
                        ++$total_import;
                    }
                }
                break;

            case "projects":
                $total_import = 0;
                foreach ($asana_workspaces_gid as $ws_gid){
                    $projects = $client->projects->findByWorkspace($ws_gid,array(),array('opt_fields' => 'id,name,team.name,archived,created_at,due_date'));
                    foreach ($projects as $project) {
                        $created_at = null;
                        $due_date = null;
                        $team_name = null;
                        if(isset($project->created_at))
                            $created_at = date('Y-m-d h:i:s', strtotime($project->created_at));
                        if(isset($project->due_date))
                            $due_date = date('Y-m-d h:i:s', strtotime($project->due_date));
                        if(isset($project->team))
                            $team_name = $project->team->name;
                        $asana_user = table::asana_projects()->updateOrInsert(
                            ['gid' => $project->id],
                            [   'name' => $project->name, 
                                'team_name' => $team_name,
                                'archived' => $project->archived,
                                'created_at' => $created_at,
                                'due_date' => $due_date,
                            ]
                        );
                        ++$total_import;
                    }
                }
                break;

            case "tasks":
                $total_import = 0;
                $projects = table::asana_projects()->select('gid')->get()->toArray();
                foreach ($projects as $proj_gid) {
                    $tasks = $client->tasks->findByProject($proj_gid->gid,array(),array('opt_fields' => 'id,name,assignee.id,completed,completed_at,created_at,due_on'));
                    foreach ($tasks as $task) {

                        $completed = 0;
                        $completed_at = null;
                        $created_at = null;
                        $due_on = null;
                        $user_gid = null;
                        if($task->completed)
                            $completed = 1;
                        if(isset($task->completed_at))
                            $completed_at = date('Y-m-d h:i:s', strtotime($task->completed_at));
                        if(isset($task->created_at))
                            $created_at = date('Y-m-d h:i:s', strtotime($task->created_at));
                        if(isset($task->due_on))
                            $due_on = date('Y-m-d h:i:s', strtotime($task->due_on));
                        if(isset($task->assignee))
                            $user_gid = $task->assignee->id;

                        if($user_gid != null && $task->name != null)
                        {
                            if(!Str::endsWith($task->name, ':')){
                                $asana_tasks = table::asana_tasks()->updateOrInsert(
                                    ['gid' => $task->id],
                                    [   'name' => $task->name, 
                                        'user_gid' => $user_gid,
                                        'projects_gid' => $proj_gid->gid,
                                        'completed' => $completed,
                                        'completed_at' => $completed_at,
                                        'created_at' => $created_at,
                                        'due_on' => $due_on,
                                    ]
                                );
                                ++$total_import;
                            }
                        }
                    }
                }
                break;

            case "tasklists":
                $total_import = 0;
                $users = table::asana_users()->select('user_task_list_gid','workspace_gid')->where('status',1)->get()->toArray();

                $date_limit = Carbon::now()->subDays(1)->format('Y-m-d');
 
                foreach ($users as $task_list) {
                    if(isset($task_list->user_task_list_gid)){

                        $path = sprintf("/user_task_lists/%s/tasks", $task_list->user_task_list_gid);
                        $tasks = $client->getCollection($path, array('opt_fields' => 'id,name,assignee.id,completed,completed_at,projects.id,created_at,due_on', 'completed_since' => $date_limit));

                        foreach ($tasks as $task) {

                            $completed = 0;
                            $completed_at = null;
                            $created_at = null;
                            $due_on = null;
                            $user_gid = null;
                            $project_gid = null;
                            if($task->completed)
                                $completed = 1;
                            if(isset($task->completed_at))
                                $completed_at = date('Y-m-d h:i:s', strtotime($task->completed_at));
                            if(isset($task->created_at))
                                $created_at = date('Y-m-d h:i:s', strtotime($task->created_at));
                            if(isset($task->due_on))
                                $due_on = date('Y-m-d h:i:s', strtotime($task->due_on));
                            if(isset($task->assignee))
                                $user_gid = $task->assignee->id;
                            if(isset($task->projects)){
                                if(isset($task->projects->id)){
                                    $project_gid = $task->projects->id;
                                }
                            }

                            if($user_gid != null && $task->name != null)
                            {   if(!Str::endsWith($task->name, ':')){
                                    $asana_tasks = table::asana_tasks()->updateOrInsert(
                                        ['gid' => $task->id],
                                        [   'name' => $task->name, 
                                            'user_gid' => $user_gid,
                                            'projects_gid' => $project_gid,
                                            'completed' => $completed,
                                            'completed_at' => $completed_at,
                                            'created_at' => $created_at,
                                            'due_on' => $due_on,
                                        ]
                                    );
                                    ++$total_import;
                                }
                            }
                        }
                    }
                }

                break;

            default:
                $total_import = 0;
                break;
        }

        return response()->json([
            'type' => $type,
            'status' => 'completed',
            'total_import' => $total_import,
            'asana_workspaces_gid' => $asana_workspaces_gid,
            'data' =>$data,
            'processing_time' => microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"],
        ]);
    }

   public function asanaJob($key){

        //get Cron job key
        if($key !== config('app.cron_key')){
            return response()->json([
                "error" => "Whoops! Please use valid key to call the Cron job"
            ]);            
        }

        //check job schedule
        $asana_jobs = table::asana_jobs()->where("status", 0)->first();

        if(isset($asana_jobs)){

            //get Asana personal access token
            $token = config('app.asana_personal_access_token');
            if(!isset($token)){
                return response()->json([
                    "error" => "Whoops! Missing Asana personal access token"
                ]);          
            }
                
            //connect to Asana API
            $client = Client::accessToken($token);  
            if(!isset($client)){
                return response()->json([
                    "error" => "Whoops! Invalid Asana personal access token"
                ]);          
            }

            //get all workspaces gid
            $asana_workspaces = table::settings()->value('asana_workspaces');
            $asana_workspaces_gid = array();
            if(isset($asana_workspaces)){
                $w = explode(",", $asana_workspaces);
                
                $workspaces = $client->workspaces->findAll();
                foreach ($workspaces as $workspace) {
                    foreach ($w as $workspace_name){
                        if($workspace_name === $workspace->name){
                            $asana_workspaces_gid[] = $workspace->id;
                        }
                    }
                }
            }

            switch ($asana_jobs->type) {

                case "users":
                    table::asana_jobs()->where('id', $asana_jobs->id)->update(["status" => 1,"updated_at" => now()]);

                    $total_import = 0;
                    foreach ($asana_workspaces_gid as $ws_gid){
                        $users = $client->users->findByWorkspace($ws_gid,array(),array('opt_fields' => 'id,name,email'));
                        foreach ($users as $user) {

                            $asana_user = table::asana_users()->where("gid",$user->id)->count();

                            if($asana_user < 1){
                                $path = sprintf("/users/%s/user_task_list", $user->id);
                                $user_task_list = $client->get($path, array('workspace' => $ws_gid));
                                $user_task_list_gid = null;

                                if(isset($user_task_list))
                                    $user_task_list_gid = $user_task_list->id;

                                $asana_user = table::asana_users()->updateOrInsert(
                                    ['gid' => $user->id],
                                    [   'email' => $user->email, 
                                        'name' => $user->name,
                                        'user_task_list_gid' => $user_task_list_gid,
                                        'workspace_gid' => $ws_gid,
                                    ]
                                );
                            } else {
                                $asana_user = table::asana_users()->updateOrInsert(
                                    ['gid' => $user->id],
                                    [   'email' => $user->email, 
                                        'name' => $user->name,
                                        'workspace_gid' => $ws_gid,
                                    ]
                                );
                            }
                            ++$total_import;
                        }
                    }

                    table::asana_jobs()->where('id', $asana_jobs->id)->update(["status" => 2,"updated_at" => now()]);
                    break;

                case "projects":
                    table::asana_jobs()->where('id', $asana_jobs->id)->update(["status" => 1,"updated_at" => now()]);

                    $total_import = 0;
                    foreach ($asana_workspaces_gid as $ws_gid){
                        $projects = $client->projects->findByWorkspace($ws_gid,array(),array('opt_fields' => 'id,name,team.name,archived,created_at,due_date'));
                        foreach ($projects as $project) {
                            $created_at = null;
                            $due_date = null;
                            $team_name = null;
                            if(isset($project->created_at))
                                $created_at = date('Y-m-d h:i:s', strtotime($project->created_at));
                            if(isset($project->due_date))
                                $due_date = date('Y-m-d h:i:s', strtotime($project->due_date));
                            if(isset($project->team))
                                $team_name = $project->team->name;
                            $asana_user = table::asana_projects()->updateOrInsert(
                                ['gid' => $project->id],
                                [   'name' => $project->name, 
                                    'team_name' => $team_name,
                                    'archived' => $project->archived,
                                    'created_at' => $created_at,
                                    'due_date' => $due_date,
                                ]
                            );
                            ++$total_import;
                        }
                    }

                    table::asana_jobs()->where('id', $asana_jobs->id)->update(["status" => 2,"updated_at" => now()]);

                case "tasklists":
                    table::asana_jobs()->where('id', $asana_jobs->id)->update(["status" => 1,"updated_at" => now()]);

                    $total_import = 0;
                    $date_limit = Carbon::now()->subDays(5)->format('Y-m-d');
                    if(isset($asana_jobs->gid)){

                        $path = sprintf("/user_task_lists/%s/tasks", $asana_jobs->gid);
                        $tasks = $client->getCollection($path, array('opt_fields' => 'id,name,assignee.id,completed,completed_at,projects.id,created_at,due_on', 'completed_since' => $date_limit));

                        foreach ($tasks as $task) {

                            $completed = 0;
                            $completed_at = null;
                            $created_at = null;
                            $due_on = null;
                            $user_gid = null;
                            $project_gid = null;
                            if($task->completed)
                                $completed = 1;
                            if(isset($task->completed_at))
                                $completed_at = date('Y-m-d h:i:s', strtotime($task->completed_at));
                            if(isset($task->created_at))
                                $created_at = date('Y-m-d h:i:s', strtotime($task->created_at));
                            if(isset($task->due_on))
                                $due_on = date('Y-m-d h:i:s', strtotime($task->due_on));
                            if(isset($task->assignee))
                                $user_gid = $task->assignee->id;
                            if(isset($task->projects)){
                                if(isset($task->projects->id)){
                                    $project_gid = $task->projects->id;
                                }
                            }

                            if($user_gid != null && $task->name != null)
                            {
                                if(!Str::endsWith($task->name, ':')){
                                    $asana_tasks = table::asana_tasks()->updateOrInsert(
                                        ['gid' => $task->id],
                                        [   'name' => $task->name, 
                                            'user_gid' => $user_gid,
                                            'projects_gid' => $project_gid,
                                            'completed' => $completed,
                                            'completed_at' => $completed_at,
                                            'created_at' => $created_at,
                                            'due_on' => $due_on,
                                        ]
                                    );
                                    ++$total_import;
                                }
                            }
                        }
                    }
                    
                    table::asana_jobs()->where('id', $asana_jobs->id)->update(["status" => 2,"updated_at" => now()]);

                case "summary":
                    table::asana_jobs()->where('id', $asana_jobs->id)->update(["status" => 1,"updated_at" => now()]);

                    $total_import = 0;
                    if(isset($asana_jobs->gid)){

                        $date_today = Carbon::now()->format('Y-m-d');
                        $year_today = (int)Carbon::now()->format('Y');
                        $month_today = (int)Carbon::now()->format('m');
                        $ww_today = (int)Carbon::now()->format('W');

                        $tasks_open = table::asana_tasks()->where(["user_gid" => $asana_jobs->gid, "completed" => '0'])->count();
                        $tasks_overdue = table::asana_tasks()->where(["user_gid" => $asana_jobs->gid, "completed" => '0'])->where('due_on', '<' , Carbon::today())->count();
                        $tasks_completed = table::asana_tasks()->where(["user_gid" => $asana_jobs->gid, "completed" => '1', ])->whereDate('completed_at', Carbon::today())->count();
                        $tasks_completed_ontime = table::asana_tasks()->where(["user_gid" => $asana_jobs->gid, "completed" => '1', ])->whereDate('completed_at', Carbon::today())->whereRaw('(completed_at <= due_on OR due_on IS NULL)')->count();
                        $tasks_completed_overdue = table::asana_tasks()->where(["user_gid" => $asana_jobs->gid, "completed" => '1', ])->whereDate('completed_at', Carbon::today())->whereRaw('completed_at > due_on')->count();
                        $tasks_created = table::asana_tasks()->where(["user_gid" => $asana_jobs->gid])->whereDate('created_at', Carbon::today())->count();

                        $asana_summary = table::asana_summary()->updateOrInsert(
                                    [   'user_gid' => $asana_jobs->gid,
                                        'summary_date' => $date_today,
                                    ],
                                    [   'summary_ww' => $ww_today, 
                                        'summary_month' => $month_today,
                                        'summary_year' => $year_today,
                                        'tasks_open' => $tasks_open,
                                        'tasks_overdue' => $tasks_overdue,
                                        'tasks_completed' => $tasks_completed,
                                        'tasks_completed_ontime' => $tasks_completed_ontime,
                                        'tasks_completed_overdue' => $tasks_completed_overdue,
                                        'tasks_created' => $tasks_created,
                                        'updated_at' =>  now(),
                                    ]
                                );

                    }

                    table::asana_jobs()->where('id', $asana_jobs->id)->update(["status" => 2,"updated_at" => now()]);

                default:
                    break;
            }


        }
        else{
            //prepare job schedule
            table::asana_jobs()->insert(['type' => 'users']);
            table::asana_jobs()->insert(['type' => 'projects']);

            $users = table::asana_users()->select('gid','user_task_list_gid','workspace_gid')->where('status',1)->get()->toArray();
            foreach ($users as $task_list) {
                table::asana_jobs()->insert(['type' => 'tasklists', 'gid' => $task_list->user_task_list_gid]);
                table::asana_jobs()->insert(['type' => 'summary', 'gid' => $task_list->gid]);
            }


        }

        return response()->json([
            'status' => 'completed',
            'processing_time' => microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"],
        ]); 

    }

}

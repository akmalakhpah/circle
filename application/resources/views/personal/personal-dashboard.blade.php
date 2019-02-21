@extends('layouts.personal')
    
    @section('styles')
        
    @endsection

    @section('content')
    
        <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
            <h2 class="page-title">Dashboard</h2>
            </div>    
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="ui icon user outline"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">@isset($profile->firstname){{ $profile->firstname }}@endisset @isset($profile->lastname) {{ $profile->lastname }} @endisset</span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-blue" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>Title</td>
                                            <td>@isset($profile->jobposition) {{ $profile->jobposition }} @endisset</td>
                                        </tr>
                                        <tr>
                                            <td>ID No.</td>
                                            <td>@isset($profile->idno) {{ $profile->idno }} @endisset</td>
                                        </tr>
                                        <tr>                                        
                                        <?php
                                        $joined = null;
                                        if(isset($profile->startdate)){
                                            $datetime1 = new DateTime($profile->startdate);
                                            $datetime2 = new DateTime('now');;
                                            $interval = $datetime1->diff($datetime2);
                                            $joined = $interval->format('%y years %m months ago');
                                        }
                                        ?>
                                            <td>Joined</td>
                                            <td>@isset($joined) {{ $joined }} @endisset</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-cyan"><i class="ui icon user outline circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">ALL STAFF</span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-cyan" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>Regular</td>
                                            <td>@isset($emp_typeR) {{ $emp_typeR }} @endisset</td>
                                        </tr>
                                        <tr>
                                            <td>Contract</td>
                                            <td>@isset($emp_typeC) {{ $emp_typeC }} @endisset</td>
                                        </tr>
                                        <tr>
                                            <td>Trainee</td>
                                            <td>@isset($emp_typeT) {{ $emp_typeT }} @endisset</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

         </div>

        <div class="row">

            <div class="col-md-6">
                <div class="box box-cyan">
                    <div class="box-header with-border">
                        <h3 class="box-title">Newest Employees</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                    <table class="table responsive nobordertop">
                        <thead>
                            <tr>
                                <th class="text-left">Name</th>
                                <th class="text-left">Position</th>
                                <th class="text-left">Start Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($emp_all_type)
                                @foreach ($emp_all_type as $data)
                                <tr>
                                    <td class="text-left name-title">{{ $data->lastname }}, {{ $data->firstname }}</td>
                                    <td class="text-left">{{ $data->jobposition }}</td>
                                    <td class="text-left">@php echo e(date('M d, Y', strtotime($data->startdate))) @endphp</td>
                                </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-cyan">
                    <div class="box-header with-border">
                        <h3 class="box-title">Upcoming Birthday</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                    <table class="table responsive nobordertop">
                        <thead>
                            <tr>
                                <th class="text-left">Name</th>
                                <th class="text-left">Birthday Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($emp_birthday)
                                @foreach ($emp_birthday as $data)
                                <tr>
                                    <td class="text-left name-title @if(e(date('M d')) == e(date('M d', strtotime($data->birthday)))) text-cyan @endif">{{ $data->lastname }}, {{ $data->firstname }}</td>
                                    <td class="text-left @if(e(date('M d')) == e(date('M d', strtotime($data->birthday)))) text-cyan @endif">@php echo e(date('M d', strtotime($data->birthday))) @endphp @if(e(date('M d')) == e(date('M d', strtotime($data->birthday)))) (Today) @endif</td>
                                </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

        </div>
         
        @if($setting->enable_asana_report)
        <div class="row">
            <div class="col-md-12">
            <h3 class="ui header">My Asana Reports</h3>
            </div>    
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-pink"><i class="ui icon tasks"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">MY COMPLETED TASKS</span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-pink" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>This Year</td>
                                            <td>@isset($task_year) {{ $task_year }} @endisset</td>
                                        </tr>
                                        <tr>
                                            <td>This Month</td>
                                            <td>@isset($task_month) {{ $task_month }} @endisset</td>
                                        </tr>
                                        <tr>
                                            <td>This Work Week ({{date('W')}}) </td>
                                            <td>@isset($task_week) {{ $task_week }} @endisset</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-orange"><i class="ui icon list ul"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">MY THIS YEAR OVERDUE TASKS</span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-orange" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>Overdue This Year</td>
                                            <td>@isset($task_overdue_year) {{ $task_overdue_year }} @endisset</td>
                                        </tr>
                                        <tr>
                                            <td>Longest Overdue</td>
                                            <td>@isset($task_longest_overdue_year) {{ $task_longest_overdue_year->overdue }} days @endisset</td>
                                        </tr>
                                        <tr>
                                            <td>Without Due Date</td>
                                            <td>@isset($task_without_due_year) {{ $task_without_due_year }} @endisset</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endif

        @if($setting->enable_attendance)
        <div class="row">
            <div class="col-md-12">
            <h3 class="ui header">My Attendance</h3>
            </div>    
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="ui icon clock outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">ATTENDANCE <span class="text-hint">(Current Month)</span> </span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-yellow" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>Late Arrivals</td>
                                            <td><span class="bolder">@isset($la) {{ $la }} @endisset</span></td>
                                        </tr>
                                        <tr>
                                            <td>Early Departures</td>
                                            <td><span class="bolder">@isset($ed) {{ $ed }} @endisset</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-orange"><i class="ui icon user outline circle"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Present Schedule</span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-orange" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>Time</td>
                                            <td><span class="bolder">@isset($cs->intime) {{ $cs->intime }} @endisset - @isset($cs->outime) {{ $cs->outime }} @endisset</span></td>
                                        </tr>
                                        <tr>
                                            <td>Rest Days</td>
                                            <td><span class="bolder">@isset($cs->restday) {{ $cs->restday }} @endisset</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="ui icon life ring outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">LEAVES OF ABSENCE</span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-red" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>Approved </td>
                                            <td><span class="bolder">@isset($al) {{ $al }} @endisset</span></td>
                                        </tr>
                                        <tr>
                                            <td>Pending </td>
                                            <td><span class="bolder">@isset($pl) {{ $pl }} @endisset</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-md-4">
                <div class="box box-yellow">
                    <div class="box-header with-border">
                        <h3 class="box-title">Recent Attendances</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped nobordertop">
                        <thead>
                            <tr>
                                <th class="text-left">Date</th>
                                <th class="text-left">Time</th>
                                <th class="text-left">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($a)
                            @foreach($a as $v)

                            @if($v->timein != '' && $v->timeout == '')
                            <tr>
                                <td>@php $date1 = date('M d, Y', strtotime($v->date)); @endphp
                                    {{ $date1 }}
                                </td>
                                <td>@php echo e(date('h:i:s A', strtotime($v->timein))) @endphp</td>
                                <td>Time-In</td>
                            </tr>
                            @endif
                            
                            @if($v->timein != '' && $v->timeout != '')
                            <tr>
                                <td>@php $date2 = date('M d, Y', strtotime($v->date)); @endphp
                                    {{ $date2 }}
                                </td>
                                <td>@php echo e(date('h:i:s A', strtotime($v->timeout))) @endphp</td>
                                <td>Time-Out</td>
                            </tr>
                            @endif

                            @if($v->timein != '' && $v->timeout != '')
                            <tr>
                                <td>@php $date3 = date('M d, Y', strtotime($v->date)); @endphp
                                    {{ $date3 }}
                                </td>
                                <td>@php echo e(date('h:i:s A', strtotime($v->timein))) @endphp</td>
                                <td>Time-In</td>
                            </tr>
                            @endif

                            @endforeach
                            @endisset
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box box-orange">
                    <div class="box-header with-border">
                        <h3 class="box-title">Previous Schedules</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                    <table class="table table-striped nobordertop">
                        <thead>
                            <tr>
                                <th class="text-left">Time</th>
                                <th class="text-left">From Date / Until</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($ps)
                            @foreach($ps as $s)
                            <tr>
                                <td>{{ $s->intime }} - {{ $s->outime }}</td>
                                <td>
                                    @php 
                                        $date4 = date('M d',strtotime($s->datefrom)).' - '.date('M d, Y',strtotime($s->dateto)); 
                                    @endphp
                                    {{ $date4 }}
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="box box-red">
                    <div class="box-header with-border">
                        <h3 class="box-title">Recent Leaves of Absence</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                    <table class="table table-striped nobordertop">
                        <thead>
                            <tr>
                                <th class="text-left">Description</th>
                                <th class="text-left">Date</th>
                            </tr>
                        </thead>
                            <tbody>
                                @isset($ald)
                                @foreach($ald as $l)
                                <tr>
                                    <td>{{ $l->type }}</td>
                                    <td>
                                        @php
                                            $fd = date('M', strtotime($l->leavefrom));
                                            $td = date('M', strtotime($l->leaveto));

                                            if($fd == $td){
                                                $var = date('M d', strtotime($l->leavefrom)) .' - '. date('d, Y', strtotime($l->leaveto));
                                            } else {
                                                $var = date('M d', strtotime($l->leavefrom)) .' - '. date('M d, Y', strtotime($l->leaveto));
                                            }
                                        @endphp
                                        {{ $var }}
                                    </td>
                                </tr>
                                @endforeach
                                @endisset
                            </tbody>
                    </table>
                    </div>
                </div>
            </div>

        </div>
        @endif

    </div>

    @endsection
    
    @section('scripts')
    <script type="text/javascript">
    
    </script>
    @endsection 
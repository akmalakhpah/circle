@extends('layouts.personal')

    @section('content')
    
    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">Reports</h2>
        </div>

        <div class="row">
            <div class="box box-success">
                <div class="box-body">
                <table width="100%" class="reports-table table table-striped table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Report name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a href="{{ url('personal/reports/employee-list') }}"><i class="ui icon users"></i> Staff List Report</a></td>
                        </tr>
                        @if($setting->enable_attendance)
                        <tr>
                            <td><a href="{{ url('personal/reports/employee-attendance') }}"><i class="ui icon clock"></i> Staff Attendance Report</a></td>
                        </tr>
                        <tr>
                            <td><a href="{{ url('personal/reports/employee-leaves') }}"><i class="ui icon calendar plus"></i> Staff Leaves Report</a></td>
                        </tr>
                        <tr>
                            <td><a href="{{ url('personal/reports/employee-schedule') }}"><i class="ui icon calendar alternate outline"></i> Staff Schedule Report</a></td>
                        </tr>
                        @endif
                        <tr>
                            <td><a href="{{ url('personal/reports/organization-profile') }}"><i class="ui icon chart pie"></i> Organization Profile</a></td>
                        </tr>

                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    @endsection
    
    @section('scripts')
    <script type="text/javascript">
    $(document).ready(function() {
        $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: false,sorting: false,});
    });
    </script>
    @endsection 
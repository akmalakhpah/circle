@extends('layouts.default')

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
                            <th class="odd">Last Viewed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a href="{{ url('reports/employee-list') }}"><i class="ui icon users"></i> Staff List Report</a></td>
                            <td class="odd">
                                @isset($lastviews)
                                @foreach ($lastviews as $views)
                                    @if($views->report_id == 1)
                                       {{ $views->last_viewed }}
                                    @endif
                                @endforeach
                                @endisset
                            </td>
                        </tr>
                        @if($setting->enable_attendance)
                        <tr>
                            <td><a href="{{ url('reports/employee-attendance') }}"><i class="ui icon clock"></i> Staff Attendance Report</a></td>
                            <td class="odd">
                                @isset($lastviews)
                                @foreach ($lastviews as $views)
                                    @if($views->report_id == 2)
                                       {{ $views->last_viewed }}
                                    @endif
                                @endforeach
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <td><a href="{{ url('reports/employee-leaves') }}"><i class="ui icon calendar plus"></i> Staff Leaves Report</a></td>
                            <td class="odd">
                                @isset($lastviews)
                                @foreach ($lastviews as $views)
                                    @if($views->report_id == 3)
                                       {{ $views->last_viewed }}
                                    @endif
                                @endforeach
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <td><a href="{{ url('reports/employee-schedule') }}"><i class="ui icon calendar alternate outline"></i> Staff Schedule Report</a></td>
                            <td class="odd">
                                @isset($lastviews)
                                @foreach ($lastviews as $views)
                                    @if($views->report_id == 4)
                                       {{ $views->last_viewed }}
                                    @endif
                                @endforeach
                                @endisset
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td><a href="{{ url('reports/organization-profile') }}"><i class="ui icon building"></i> Organization Demographic</a></td>
                            <td class="odd">
                                @isset($lastviews)
                                @foreach ($lastviews as $views)
                                    @if($views->report_id == 5)
                                       {{ $views->last_viewed }}
                                    @endif
                                @endforeach
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <td><a href="{{ url('reports/employee-birthdays') }}"><i class="ui icon birthday cake"></i> Staff Birthdays</a></td>
                            <td class="odd">
                                @isset($lastviews)
                                @foreach ($lastviews as $views)
                                    @if($views->report_id == 7)
                                       {{ $views->last_viewed }}
                                    @endif
                                @endforeach
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <td><a href="{{ url('reports/user-accounts') }}"><i class="ui icon address book outline"></i> User Accounts Report</a></td>
                            <td class="odd">
                                @isset($lastviews)
                                @foreach ($lastviews as $views)
                                    @if($views->report_id == 6)
                                       {{ $views->last_viewed }}
                                    @endif
                                @endforeach
                                @endisset
                            </td>
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
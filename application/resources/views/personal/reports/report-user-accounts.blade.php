@extends('layouts.personal')
    
    @section('content')
    
    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">User Accounts Report
                <a href="{{ url('personal/reports') }}" class="ui basic blue button mini offsettop5 float-right"><i class="ui icon chevron left"></i>Return</a>
            </h2> 
        </div>

        <div class="row">
            <div class="box box-aqua">
                <div class="box-body">
                    <table width="100%" class="table table-striped table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Staff Name</th>
                                <th>Email</th>
                                <th>Account Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($userAccs)
                            @foreach ($userAccs as $v)
                                <tr>
                                    <td>{{ $v->name }}</td>
                                    <td>{{ $v->email }}</td>
                                    <td>@if( $v->acc_type == 2) Admin @else Staff @endif</td>
                                    <td>@if($v->status == 1) Active @endif @if($v->status == 0) Disabled @endif</td>
                                </tr>
                            @endforeach
                            @endisset
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
        $('#dataTables-example').DataTable({responsive: true,pageLength: 25,lengthChange: false,searching: true,});
    });
    </script>
    @endsection 
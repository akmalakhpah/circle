@extends('layouts.personal')

    @section('content')
    
    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">Staff Birthdays
                <a href="{{ url('personal/reports') }}" class="ui basic blue button mini offsettop5 float-right"><i class="ui icon chevron left"></i>Return</a>
            </h2>   
        </div>

        <div class="row">
            <div class="box box-aqua">
                <div class="box-body">
                    <table width="100%" class="table table-striped table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>ID #</th>
                                <th>Staff Name</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Birthday</th>
                                <th>Contact #</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($empBday)
                            @foreach ($empBday as $v)
                                <tr>
                                    <td>{{ $v->idno }}</td>
                                    <td>{{ $v->lastname }}, {{ $v->firstname }} {{ $v->mi }}</td>
                                    <td>{{ $v->department }}</td>
                                    <td>{{ $v->jobposition }}</td>
                                    <td>
                                    @php $bdaydate = date("D, M d Y", strtotime($v->birthday)); @endphp
                                    @if($v->birthday != null)
                                        {{ $bdaydate }}
                                    @endif
                                    </td>
                                    <td>{{ $v->mobileno }}</td>
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
        $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: true,});
    });
    </script>
    @endsection 
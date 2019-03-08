@extends('layouts.default')
    
    @section('content')

    <div class="container-fluid">
        <div class="row">
        <div class="col-md-12">
            <h2 class="page-title">Edit Department</h2>
        </div>    
        </div>

        <div class="row">
        <div class="col-md-12">
            <div class="box box-aqua">
                <div class="box-content">
                <form action="{{ url('fields/department/update') }}" class="ui form" method="post" accept-charset="utf-8">
                {{ csrf_field() }}
                    <div class="field">
                        <label>Company</label>
                        <select class="ui search dropdown getemail uppercase" name="comp_code">
                            <option value="">Select Company</option>
                            @isset($comp)
                            @foreach ($comp as $data)
                                <option value="{{ $data->id }}" data-ref="{{ $data->id }}" @isset($dept->comp_code) @if($dept->comp_code == $data->id) selected @endif @endisset>{{ $data->company}}</option>
                            @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="field">
                        <label>Department Name</label>
                        <input type="text" name="department" value="@isset($dept){{$dept->department}}@endisset" class="uppercase" placeholder="Enter Name for Department">
                    </div>

                    <div class="field">
                        <label>Manager</label>
                        <select class="ui search dropdown getemail uppercase" name="manager">
                            <option value="">Select Manager</option>
                            @isset($employees)
                            @foreach ($employees as $data)
                                <option value="{{ $data->id }}" data-e="{{ $data->emailaddress }}" data-ref="{{ $data->id }}" @isset($dept->manager) @if($dept->manager == $data->id) selected @endif @endisset>{{ $data->lastname }}, {{ $data->firstname }}</option>
                            @endforeach
                            @endisset
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="id" value="@isset($dept){{$dept->id}}@endisset">
                    <button class="ui positivex blue approve small button" type="submit" name="submit"><i class="ui checkmark icon"></i> Submit</button>
                    <a href="{{ url('fields/department') }}" class="ui black grey small button"><i class="ui times icon"></i> Cancel</a>
                </div>
                </form>
                
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('scripts')
    <script>
        var selected = "@isset($dept){{$dept->comp_code}}@endisset";
        var items = selected.split(',');
        $('.ui.dropdown.multiple.leaves').dropdown('set selected', items);
    </script>
    @endsection
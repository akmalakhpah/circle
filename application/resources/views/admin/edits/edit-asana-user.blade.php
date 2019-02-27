@extends('layouts.default')
    
    @section('styles')
    @endsection

    @section('content')

    <div class="container-fluid">
        <div class="row">
        <div class="col-md-12">
            <h2 class="page-title">Edit Asana User</h2>
        </div>    
        </div>

        <div class="row">
        <div class="col-md-12">
                <div class="box box-aqua">
                    <div class="box-content">
                        
                        <form action="{{ url('asana-users/update/user') }}" class="ui form add-user" method="post" accept-charset="utf-8">
                        {{ csrf_field() }}
                            <div class="field">
                                <label>Asana User Name</label>
                                <input type="text" name="asana-user-name" value="@isset($u->name){{ $u->name }}@endisset" class="readonly uppercase" readonly>
                            </div>
                            <div class="field">
                                <label>Asana User Email</label>
                                <input type="text" name="asana-user-email" value="@isset($u->email){{ $u->email }}@endisset" class="readonly lowercase" readonly>
                            </div>


                            <div class="field">
                                <label>Staff</label>
                                <select class="ui search dropdown getemail uppercase" name="name">
                                    <option value="">Select Staff</option>
                                    @isset($employees)
                                    @foreach ($employees as $data)
                                        <option value="{{ $data->lastname }}, {{ $data->firstname }}" data-e="{{ $data->emailaddress }}" data-ref="{{ $data->id }}" @isset($u->reference) @if($u->reference == $data->id) selected @endif @endisset>{{ $data->lastname }}, {{ $data->firstname }}</option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="field">
                                <label>Email</label>
                                <input type="text" name="email" class="readonly lowercase" value="@isset($u->emailaddress){{ $u->emailaddress }}@endisset" readonly>
                            </div>
                            <div class="field">
                                <label>Status</label>
                                <select class="ui dropdown uppercase" name="status">
                                    <option value="">Select Status</option>
                                    <option value="1" @isset($u->status) @if($u->status == 1) selected @endif @endisset>Enabled</option>
                                    <option value="0" @isset($u->status) @if($u->status == 0) selected @endif @endisset>Disabled</option>
                                </select>
                            </div>
                    </div>

                    <div class="box-footer">
                        <input type="hidden" value="@isset($u->reference){{ $u->reference }}@endisset" name="ref">
                        <input type="hidden" value="@isset($u->id){{ $u->id }}@endisset" name="id">
                        <button class="ui positivex blue approve small button" type="submit" name="submit"><i class="ui checkmark icon"></i> Submit</button>
                        <a href="{{ url('asana-users') }}" class="ui black grey small button"><i class="ui times icon"></i> Cancel</a>
                    </div>

                    </form> 

            </div>
        </div>
    </div>

    @endsection

    @section('scripts')
    <script type="text/javascript">
    $(document).ready(function () {
        $('.ui.dropdown.getemail').dropdown({ onChange: function(value, text, $selectedItem) {
            $('select[name="name"] option').each(function() {
                if($(this).val()==value) {var e = $(this).attr('data-e');var r = $(this).attr('data-ref');$('input[name="email"]').val(e);$('input[name="ref"]').val(r);};
            });
        }});

    });

    </script>
    @endsection
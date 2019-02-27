@extends('layouts.default')
    
    @section('content')
    @include('admin.modals.modal-add-user')

    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">Asana Users</h2>
        </div>

        <div class="row">
            <div class="box box-aqua">
                <div class="box-body">
                    <table width="100%" class="table table-striped table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Asana User Name</th>
                                <th>Asana User Email</th>
                                <th>Staff</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                           @isset($asana_users)
                            @foreach ($asana_users as $val)
                            <tr>
                                <td>{{ $val->name }}</td>
                                <td>{{ $val->email }}</td>
                                <td>@isset($val->employee)<a href="{{ url('/profile/view/'.$val->reference) }}">{{ $val->employee }}</a>@endisset</td>
                                <td>
                                    <span>
                                    @if($val->status == '1') 
                                        Enabled
                                    @else
                                        Disabled
                                    @endif
                                    </span>
                                </td>
                                <td class="align-right">
                                    <a href="{{ url('/asana-users/edit/'.$val->id) }}" class="ui circular basic icon button tiny"><i class="icon edit outline"></i></a>
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

    @endsection

    @section('scripts')
    <script type="text/javascript">
    $(document).ready(function() {
        $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: true,});
    });

    $('.ui.dropdown.getemail').dropdown({ onChange: function(value, text, $selectedItem) {
        $('select[name="name"] option').each(function() {
            if($(this).val()==value) {var e = $(this).attr('data-e');var r = $(this).attr('data-ref');$('input[name="email"]').val(e);$('input[name="ref"]').val(r);};
        });
    }});
    
    </script>
    @endsection
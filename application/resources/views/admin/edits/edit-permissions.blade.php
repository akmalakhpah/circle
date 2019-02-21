@extends('layouts.default')
    
    @section('styles')

    @endsection

    @section('content')

    <div class="container-fluid">
        <div class="row">
        <div class="col-md-12">
            <h2 class="page-title">Edit Permissions</h2>
        </div>    
        </div>

        <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-content">
                <form action="{{ url('users/roles/permissions/update') }}" class="ui form grid" method="post" accept-charset="utf-8">
                {{ csrf_field() }}
                
                <div class="eight wide column">
                    <div class="ui relaxed list">
                        <h3 class="ui header">Dashboard</h3>
                        <div class="item">
                            <div class="ui master checkbox">
                                <input type="checkbox" @isset($d) @if(in_array('1', $d) == true) checked @endif @endisset name="perms[]" value="1">
                                <label>View Dashboard</label>
                            </div>
                        </div>
                        

                        <h3 class="ui header">Staff</h3>
                        <div class="item">
                            <div class="ui master checkbox">
                                <input type="checkbox" @isset($d) @if(in_array('2', $d) == true) checked @endif @endisset name="perms[]" value="2">
                                <label>View Staff</label>
                            </div>
                            <div class="list">
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox" @isset($d) @if(in_array('21', $d) == true) checked @endif @endisset name="perms[]" value="21">
                                        <label>Add Staff</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox" @isset($d) @if(in_array('22', $d) == true) checked @endif @endisset name="perms[]" value="22">
                                        <label>View Staff</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox" @isset($d) @if(in_array('23', $d) == true) checked @endif @endisset name="perms[]" value="23">
                                        <label>Edit Staff</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox" @isset($d) @if(in_array('24', $d) == true) checked @endif @endisset name="perms[]" value="24">
                                        <label>Delete Staff</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox" @isset($d) @if(in_array('25', $d) == true) checked @endif @endisset name="perms[]" value="25">
                                        <label>Archive Staff</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h3 class="ui header">Attendance</h3>
                        <div class="item">
                            <div class="ui master checkbox">
                                <input type="checkbox" @isset($d) @if(in_array('3', $d) == true) checked @endif @endisset name="perms[]" value="3">
                                <label>View Attendance</label>
                            </div>
                            <div class="list">
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox" @isset($d) @if(in_array('31', $d) == true) checked @endif @endisset name="perms[]" value="31">
                                        <label>Edit Attendance</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox" @isset($d) @if(in_array('32', $d) == true) checked @endif @endisset name="perms[]" value="32">
                                        <label>Delete Attendance</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h3 class="ui header">Schedules</h3>
                        <div class="item">
                            <div class="ui master checkbox">
                                <input type="checkbox" @isset($d) @if(in_array('4', $d) == true) checked @endif @endisset name="perms[]" value="4">
                                <label>View Schedules</label>
                            </div>
                            <div class="list">
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox" @isset($d) @if(in_array('41', $d) == true) checked @endif @endisset name="perms[]" value="41">
                                        <label>Add Schedules</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox" @isset($d) @if(in_array('42', $d) == true) checked @endif @endisset name="perms[]" value="42">
                                        <label>Edit Schedules</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox" @isset($d) @if(in_array('43', $d) == true) checked @endif @endisset name="perms[]" value="43">
                                        <label>Delete Schedules</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox" @isset($d) @if(in_array('44', $d) == true) checked @endif @endisset name="perms[]" value="44">
                                        <label>Archive Schedules</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h3 class="ui header">Leaves</h3>
                        <div class="item">
                            <div class="ui master checkbox">
                                <input type="checkbox" @isset($d) @if(in_array('5', $d) == true) checked @endif @endisset  name="perms[]" value="5">
                                <label>View Leaves</label>
                            </div>
                            <div class="list">
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox" @isset($d) @if(in_array('51', $d) == true) checked @endif @endisset name="perms[]" value="51">
                                        <label>Add Leaves</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox" @isset($d) @if(in_array('52', $d) == true) checked @endif @endisset name="perms[]" value="52">
                                        <label>Edit Leaves</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox" @isset($d) @if(in_array('53', $d) == true) checked @endif @endisset name="perms[]" value="53">
                                        <label>Delete Leaves</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- endof ui celled list -->
                </div>
                   

                <div class="eight wide column">
                    <div class="ui relaxed list">
                        <h3 class="ui header">Settings</h3>
                        <div class="item">
                            <div class="ui master checkbox">
                                <input type="checkbox"@isset($d) @if(in_array('9', $d) == true) checked @endif @endisset name="perms[]" value="9">
                                <label>View Settings</label>
                            </div>
                        </div>

                        <h3 class="ui header">Reports</h3>
                        <div class="item">
                            <div class="ui master checkbox">
                                <input type="checkbox"@isset($d) @if(in_array('7', $d) == true) checked @endif @endisset name="perms[]" value="7">
                                <label>View Reports</label>
                            </div>
                        </div>

                        <h3 class="ui header">Users</h3>
                        <div class="item">
                            <div class="ui master checkbox">
                                <input type="checkbox"@isset($d) @if(in_array('8', $d) == true) checked @endif @endisset name="perms[]" value="8">
                                <label>View Users</label>
                            </div>
                            <div class="list">
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox"@isset($d) @if(in_array('81', $d) == true) checked @endif @endisset name="perms[]" value="81">
                                        <label>Add Users</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox"@isset($d) @if(in_array('82', $d) == true) checked @endif @endisset name="perms[]" value="82">
                                        <label>Edit Users</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox"@isset($d) @if(in_array('83', $d) == true) checked @endif @endisset name="perms[]" value="83">
                                        <label>Delete Users</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h3 class="ui header">User Roles</h3>
                        <div class="item">
                            <div class="ui master checkbox">
                                <input type="checkbox"@isset($d) @if(in_array('10', $d) == true) checked @endif @endisset name="perms[]" value="10">
                                <label>View User Roles</label>
                            </div>
                            <div class="list">
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox"@isset($d) @if(in_array('101', $d) == true) checked @endif @endisset name="perms[]" value="101">
                                        <label>Add Roles</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox"@isset($d) @if(in_array('102', $d) == true) checked @endif @endisset name="perms[]" value="102">
                                        <label>Edit Roles</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox"@isset($d) @if(in_array('103', $d) == true) checked @endif @endisset  name="perms[]" value="103">
                                        <label>Set Permission</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox"@isset($d) @if(in_array('103', $d) == true) checked @endif @endisset name="perms[]" value="104">
                                        <label>Delete Roles</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h3 class="ui header">Form Data</h3>
                        <div class="item">
                            <div class="list">
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox"@isset($d) @if(in_array('111', $d) == true) checked @endif @endisset name="perms[]" value="111">
                                        <label>Manage Company</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox"@isset($d) @if(in_array('112', $d) == true) checked @endif @endisset  name="perms[]" value="112">
                                        <label>Manage Departments</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox"@isset($d) @if(in_array('113', $d) == true) checked @endif @endisset name="perms[]" value="113">
                                        <label>Manage Job Title(s)</label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="ui child checkbox">
                                        <input type="checkbox"@isset($d) @if(in_array('114', $d) == true) checked @endif @endisset name="perms[]" value="114">
                                        <label>Manage Leave Types</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- endof ui celled list -->
                </div>

                <div class="box-footer sixteen wide column">
                    <input type="hidden" value="@isset($id) {{ $id }} @endisset" name="role_id">
                    <button class="ui positivex blue approve small button" type="submit" name="submit"><i class="ui checkmark icon"></i> Submit</button>
                    <a href="{{ url('users/roles') }}" class="ui grey cancel small button"><i class="ui times icon"></i> Cancel</a>
                </div>
                </form>

                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('scripts')
    <script type="text/javascript">
        $('.list .master.checkbox').checkbox({
            onChecked: function() {
            var
                $childCheckbox  = $(this).closest('.checkbox').siblings('.list').find('.checkbox');
                $childCheckbox.checkbox('check');
            },
            onUnchecked: function() {
            var
                $childCheckbox  = $(this).closest('.checkbox').siblings('.list').find('.checkbox');
                $childCheckbox.checkbox('uncheck');
            }
        });

        $('.list .child.checkbox').checkbox({
                fireOnInit : true,
                onChange   : function() {
                var
                    $listGroup      = $(this).closest('.list'),
                    $parentCheckbox = $listGroup.closest('.item').children('.checkbox'),
                    $checkbox       = $listGroup.find('.checkbox'),
                    allChecked      = true,
                    allUnchecked    = true;
                $checkbox.each(function() {
                    if( $(this).checkbox('is checked') ) { allUnchecked = false; }
                    else { allChecked = false; }
                });
                if(allChecked) { $parentCheckbox.checkbox('set checked'); }
                else if(allUnchecked) { $parentCheckbox.checkbox('set unchecked'); }
                else { $parentCheckbox.checkbox('set indeterminate'); }
                }
            });
    </script>

    @endsection
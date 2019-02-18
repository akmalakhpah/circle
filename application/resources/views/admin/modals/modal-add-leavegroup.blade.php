
<div class="ui modal medium add">
    <div class="header">Add New Leave</div>
    <div class="content">
        <form action="{{ url('fields/leavetype/leave-groups/add') }}" class="ui form" method="post" accept-charset="utf-8">
        {{ csrf_field() }}
            <div class="field">
                <label>Leave Group name</label>
                <input type="text" name="leavegroup" value="" class="uppercase" placeholder="Enter Leave Group Name">
            </div>
            <div class="field">
                <label>Description</label>
                <input type="text" name="description" value="" class="uppercase" placeholder="Enter Description for Leave Group">
            </div>

            <div class="field">
                <label>Leave Privileges</label>
                <select class="ui search dropdown selection multiple uppercase" name="leaveprivileges[]" multiple="">
                    <option value="">Select Leave Privileges</option>
                    @isset($lt) 
                        @foreach($lt as $leave) 
                            <option value="{{ $leave->id }}">{{ $leave->leavetype }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>

            <div class="field">
                <label>Status</label>
                <select class="ui dropdown uppercase" name="status">
                    <option value="">Select Status</option>
                    <option value="1">Active</option>
                    <option value="0">Disabled</option>
                </select>
            </div>
    </div>
    <div class="actions">
        <button class="ui positivex blue small button approve" type="submit" name="submit"><i class="ui checkmark icon"></i> Submit</button>
        <button class="ui grey small button cancel" type="button"><i class="ui times icon"></i> Cancel</button>
    </div>
    </form>  
</div>

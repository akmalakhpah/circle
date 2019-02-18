<div class="ui modal small edit">
    <div class="header">Edit Role</div>
    <div class="content">
    <form action="{{ url('users/roles/update') }}" class="ui form" method="post" accept-charset="utf-8">
    {{ csrf_field() }}
        <div class="field">
            <label>Role Name</label>
            <input class="uppercase" name="role_name" value="" type="text">
        </div>
        <div class="field">
            <label>Status</label>
            <select name="state" class="ui dropdown state uppercase">
                <option value="Active">Active</option>
                <option value="Disabled">Disabled</option>
            </select>
        </div>
    </div>
    <div class="actions">
        <input type="hidden" value="" name="id" class="" readonly="">
        <button class="ui positivex blue approve small button" type="submit" name="submit"><i class="ui checkmark icon"></i> Submit</button>
        <button class="ui grey cancel small button" type="button"><i class="ui times icon"></i> Cancel</button>
    </div>
    </form>  
</div>

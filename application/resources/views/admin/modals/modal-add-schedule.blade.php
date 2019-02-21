<div class="ui modal medium add">
    <div class="header">Add New Schedule</div>
    <div class="content">
        <form action="{{ url('schedules/add') }}" class="ui form" method="post" accept-charset="utf-8">
        {{ csrf_field() }}
            <div class="field">
                <label>Staff</label>
                <select class="ui search dropdown getid uppercase" name="employee">
                    <option value="">Select Staff</option>
                    @isset($employee)
                    @foreach ($employee as $data)
                        <option value="{{ $data->lastname }}, {{ $data->firstname }}" data-id="{{ $data->id }}">{{ $data->lastname }}, {{ $data->firstname }}</option>
                    @endforeach
                    @endisset
                </select>
            </div>

            <div class="two fields">
                <div class="field">
                    <label for="">Start Time</label>
                    <input type="text" placeholder="00:00 AM" name="intime" class="jtimepicker" />
                </div>
                <div class="field">
                    <label for="">Off Time</label>
                    <input type="text" placeholder="00:00 PM" name="outime" class="jtimepicker" />
                </div>
            </div>

            <div class="two fields">
                <div class="field">
                    <label for="">From (Date)</label>
                    <input type="text" placeholder="Date" name="datefrom" id="datefrom" class="airdatepicker" />
                </div>
                <div class="field">
                    <label for="">To (Date)</label>
                    <input type="text" placeholder="Date" name="dateto" id="dateto" class="airdatepicker" />
                </div>
            </div>

            <div class="eight wide field">
                <label for="">Total Hours</label>
                <input type="number" placeholder="00" name="hours" />
            </div>


           <div class="grouped fields">
                <label>Rest day(s)</label>
                <div class="field">
                <div class="ui checkbox sunday">
                    <input type="checkbox" name="restday[]" value="Sunday">
                    <label>Sunday</label>
                </div>
                </div>
                <div class="field">
                <div class="ui checkbox ">
                    <input type="checkbox" name="restday[]" value="Monday">
                    <label>Monday</label>
                </div>
                </div>
                <div class="field">
                <div class="ui checkbox ">
                    <input type="checkbox" name="restday[]" value="Tuesday">
                    <label>Tuesday</label>
                </div>
                </div>
                <div class="field">
                <div class="ui checkbox ">
                    <input type="checkbox" name="restday[]" value="Wednesday">
                    <label>Wednesday</label>
                </div>
                </div>
                <div class="field">
                <div class="ui checkbox ">
                    <input type="checkbox" name="restday[]" value="Thursday">
                    <label>Thursday</label>
                </div>
                </div>
                <div class="field">
                <div class="ui checkbox ">
                    <input type="checkbox" name="restday[]" value="Friday">
                    <label>Friday</label>
                </div>
                </div>
                <div class="field" style="padding:0">
                <div class="ui checkbox saturday">
                    <input type="checkbox" name="restday[]" value="Saturday">
                    <label>Saturday</label>
                </div>
                </div>
            </div>
            
    </div>
    <div class="actions">
        <input type="hidden" name="id" value="">
        <button class="ui positivex blue small button approve" type="submit" name="submit"><i class="ui checkmark icon"></i> Submit</button>
        <button class="ui grey small button cancel" type="button"><i class="ui times icon"></i> Cancel</button>
    </div>
    </form>  
</div>

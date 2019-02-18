@extends('layouts.clock')
    
    @section('styles')
        
    @endsection

    @section('content')
    
    <div class="container-fluid">

        <div class="fixedcenter">
            <div class="clockwrapper">
                <div class="clockinout">
                    <button class="btnclock timein active" data-type="timein">Time In</button>
                    <button class="btnclock timeout" data-type="timeout">Time Out</button>
                </div>
            </div>

            <div class="clockwrapper">
                <div class="timeclock">
                    <span id="daytoday" class="clock-text"></span>
                    <span id="timetoday" class="clock-time"></span>
                    <span id="datetoday" class="clock-day"></span>
                </div>
            </div>

            <div class="clockwrapper">
                <div class="userinput">
                    
                    <form action="" method="get" accept-charset="utf-8" class="ui form">
                        @isset($clock_comment)
                            @if($clock_comment == 1) 
                                <div class="inline field comment">
                                    <textarea name="comment" class="uppercase lightblue" rows="1" placeholder="Enter comment" value=""></textarea>
                                </div> 
                            @endif
                        @endisset
                        <div class="inline field">
                            <label class="small">ID :</label>
                            <input class="small" name="idno" value="" type="text" placeholder="Your ID Number" required="">
                            <input type="hidden" id="_url" value="{{url('/')}}">
                            <button id="btnclockin" type="button" name="submit" class="ui positivex blue small button">Confirm</button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="message-after">
                <p> 
                    <span id="greetings">Welcome!</span> 
                    <span id="fullname"></span>
                </p>

                <p id="messagewrap">
                    <span id="type"></span>
                    <span id="message"></span> 
                    <span id="time"></span>
                </p>
            </div>
        </div>

    </div>

    @endsection

    @section('scripts')
    
    <script type="text/javascript">

    function date_time(timetoday) {
            date = new Date();
            h = date.getHours();
            hours = ((h + 11) % 12 + 1);
            var ampm = h >= 12 ? 'PM' : 'AM';
            if(hours<10) { hours = "0"+hours; }
            m = date.getMinutes();
            if(m<10) { m = "0"+m; }
            s = date.getSeconds();
            if(s<10) { s = "0"+s; }
            timecurrent = hours+':'+m+':'+s+' '+ampm;
            document.getElementById(timetoday).innerHTML = timecurrent;
            setTimeout('date_time("'+timetoday+'");','1000');
            return true;
    }

    function date_today(datetoday) {
            date = new Date;
            year = date.getFullYear();
            month = date.getMonth();
            months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
            d = date.getDate();
            day = date.getDay();
            days = new Array('Sunday,', 'Monday,', 'Tuesday,', 'Wednesday,', 'Thursday,', 'Friday,', 'Saturday,');
            datecurrent = months[month]+' '+d+', '+year;
            document.getElementById(datetoday).innerHTML = datecurrent;
            return true;
    }

    function day_today(daytoday) {
            date = new Date;
            year = date.getFullYear();
            month = date.getMonth();
            months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
            d = date.getDate();
            day = date.getDay();
            days = new Array('Sunday,', 'Monday', 'Tuesday', 'Wednesday', 'Thursday,', 'Friday', 'Saturday');
            daycurrent = days[day];
            document.getElementById(daytoday).innerHTML = daycurrent;
            return true;
    }

    window.onload = day_today('daytoday');
    window.onload = date_time('timetoday');
    window.onload = date_today('datetoday');

    $('.btnclock').click(function(event) {
        var is_comment = $(this).text();
        if (is_comment == "Time In") {
            $('.comment').slideDown('200').show();
        } else {
            $('.comment').slideUp('200');
        }
        $('.btnclock').removeClass('active animated fadeIn')
        $(this).toggleClass('active animated fadeIn');
    });

    $('#btnclockin').click(function(event) {
        var url = $("#_url").val();
        var type = $('.btnclock.active').text();
        var idno = $('input[name="idno"]').val();
        var comment = $('textarea[name="comment"]').val();

        $.ajax({
            url: url + '/attendance/add',type: 'get',dataType: 'json',data: {idno: idno, type: type, clock_comment: comment},headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },

            success: function(response) {
                if(response['error'] != null) {
                    $('#type, #fullname').text("").hide();
                    $('#time').html("").hide();
                    $('.message-after').removeClass("ok");
                    
                    $('#message').text(response['error']);
                    $('.message-after').addClass('notok').slideToggle().slideDown('200');
                }  else if(response['error'] != null) {
                    function prefix() {
                        var gender = response['gender'];
                        var civilstatus = response['civilstatus'];
                        if (gender == 'MALE') { if(civilstatus == 'SINGLE') { return 'Mr ';  } else if(civilstatus == 'MARRIED') { return 'Sir '; } else if(civilstatus == 'ANULLED') { return 'Mr '; } else if(civilstatus == 'LEGALLY SEPARATED') { return 'Mr '; } else { return ''; } }
                        if (gender == 'FEMALE') { if(civilstatus == 'SINGLE') {return 'Miss '; } else if(civilstatus == 'MARRIED') {return 'Mrs ';} else if(civilstatus == 'ANULLED') {return 'Ms ';} else if(civilstatus == 'LEGALLY SEPARATED') {return 'Ms ';} else {return '';} }
                    }
                    $('#type, #fullname').text("");
                    $('#time').html("");
                    $('.message-after').removeClass("ok");
                    
                    $('#message').text(response['error']);
                    $('#fullname').text(prefix() + ' ' + response['employee']);
                    $('.message-after').addClass('notok').slideToggle().slideDown('200');
                } else {
                    function mi() {
                        var mi = response['mi'];
                        if(mi == null) { return ''; } else {  return mi + '.'; }
                    }
                    function prefix() {
                        var gender = response['gender'];
                        var civilstatus = response['civilstatus'];
                        if (gender == 'MALE') { if(civilstatus == 'SINGLE') { return 'Mr ';  } else if(civilstatus == 'MARRIED') { return 'Sir '; } else if(civilstatus == 'ANULLED') { return 'Mr '; } else if(civilstatus == 'LEGALLY SEPARATED') { return 'Mr '; } else { return ''; } }
                        if (gender == 'FEMALE') { if(civilstatus == 'SINGLE') {return 'Miss '; } else if(civilstatus == 'MARRIED') {return 'Mrs ';} else if(civilstatus == 'ANULLED') {return 'Ms ';} else if(civilstatus == 'LEGALLY SEPARATED') {return 'Ms ';} else {return '';} }
                    }

                    $('#type, #fullname, #message').text("").show();
                    $('#time').html("").show();
                    $('.message-after').removeClass("notok");

                    $('#type').text(response['type']);
                    $('#fullname').text(prefix() + ' ' + response['lastname'] + ', ' + response['firstname'] + ' ' + mi());
                    $('#time').html('at ' + '<span id=clocktime>' + response['time'] + '</span>' + '.' + '<span id=clockstatus> Success!</span>');
                    $('.message-after').addClass('ok').slideToggle().slideDown('200');
                }
            }
        })
    });

    </script> 

    @endsection
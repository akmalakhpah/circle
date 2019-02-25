@extends('layouts.personal')
    
    @section('content')
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">Asana Task Reports
                    <button class="ui basic button mini offsettop5 float-right d-none d-sm-block" id="toggleview"><i class="ui icon columns"></i>View</button> 
                    <a href="{{ url('personal/reports') }}" class="ui blue basic button mini offsettop5 float-right"><i class="ui icon chevron left"></i>Return</a>
                </h2>
            </div>    
        </div>

        <div class="row columnview">

            <div class="colview col-md-12">
               <form action="{{ url('personal/reports/asana-task') }}" method="post" accept-charset="utf-8" class="ui small form form-filter" id="filterform">
                    {{ csrf_field() }}
                    <div class="inline three fields">
                        <div class="three wide field">
                            <select name="type" class="ui search dropdown action getid">
                                <option value="week" @if($type=='week') selected @endif>Work Week</option>
                                <option value="month" @if($type=='month') selected @endif>Month</option>
                                <option value="year" @if($type=='year') selected @endif>Year</option>
                            </select>
                        </div>
                        <input type="hidden" name="emp_id" value="">
                        <button id="btnfilter" class="ui icon button positive small inline-button"><i class="ui icon filter alternate"></i> Filter</button>
                    </div>
                </form>
            </div>

            <div class="colview col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Completed Tasks by {{ ucwords($type) }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="canvas-wrapper">
                            <canvas class="chart" id="completedtasks"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="colview col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Overdue Tasks by {{ ucwords($type) }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="canvas-wrapper">
                            <canvas class="chart" id="overduetasks"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="colview col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Completed On-time vs Overdue for This {{ ucwords($type) }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="canvas-wrapper">
                            <canvas class="chart" id="ontime_vs_overdue"></canvas>
                        </div>  
                    </div>
                </div>
            </div>

            <div class="colview col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Overdue Period Demographics for This {{ ucwords($type) }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="canvas-wrapper">
                            <canvas class="chart" id="overdue_period"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @endsection
    
    @section('scripts')
    <script src="{{ asset('/assets/js/chartsjs.js') }}"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $('#toggleview').click(function(event) {
            $('.columnview .colview').toggleClass('col-md-12');
            $('.columnview .colview').toggleClass('col-md-6');
        });
    });

    window.chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(201, 203, 207)',
        red_clear: 'rgb(255, 99, 132, 0.2)',
        orange_clear: 'rgb(255, 159, 64, 0.2)',
        yellow_clear: 'rgb(255, 205, 86, 0.2)',
        green_clear: 'rgb(75, 192, 192, 0.2)',
        blue_clear: 'rgb(54, 162, 235, 0.2)',
        purple_clear: 'rgb(153, 102, 255, 0.2)',
        grey_clear: 'rgb(201, 203, 207, 0.2)',
    };


    // chart by completed tasks
    var completedtasks = document.getElementById("completedtasks");
    var myChart1 = new Chart(completedtasks, {
        type: 'line',
        data: {
            labels: [ @isset($ct) @php foreach ($ct as $key => $value) { echo '"' . $key . '"' . ', '; } @endphp @endisset],
            datasets: [
            {
                label: 'My Tasks',
                backgroundColor:  window.chartColors.green_clear,
                borderColor: window.chartColors.green,
                data: [ @isset($ctpdata) {{ $ctpdata }} @endisset ],
                fill: true,
            },
            {
                label: 'Other Team Members Average {{$department}}',
                backgroundColor:  window.chartColors.rgrey_clear,
                borderColor: window.chartColors.grey,
                data: [ @isset($ctddata_avg) {{ $ctddata_avg }} @endisset ],
                fill: true,
            }]
        },
        options: {
            responsive: true,
            title: {display: false,text: 'Chart'},
            legend: {position: 'top',display: true,},
            tooltips: {mode: 'index',intersect: false,},
            hover: {mode: 'nearest',intersect: true},
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: '{{ ucwords($type) }}'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Value'
                    }
                }]
            }
        }
    }
    );


    // chart by overdue tasks
    var overduetasks = document.getElementById("overduetasks");
    var myChart2 = new Chart(overduetasks, {
        type: 'line',
        data: {
            labels: [ @isset($ct) @php foreach ($ct as $key => $value) { echo '"' . $key . '"' . ', '; } @endphp @endisset],
            datasets: [
            {
                label: 'My Tasks',
                backgroundColor:  window.chartColors.red_clear,
                borderColor: window.chartColors.red,
                data: [ @isset($copdata) {{ $copdata }} @endisset ],
                fill: true,
            },
            {
                label: 'Other Team Members Average {{$department}}',
                backgroundColor:  window.chartColors.rgrey_clear,
                borderColor: window.chartColors.grey,
                data: [ @isset($coddata_avg) {{ $coddata_avg }} @endisset ],
                fill: true,
            }
            ]
        },
        options: {
            responsive: true,
            title: {display: false,text: 'Chart'},
            legend: {position: 'top',display: true,},
            tooltips: {mode: 'index',intersect: false,},
            hover: {mode: 'nearest',intersect: true},
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: '{{ ucwords($type) }}'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Value'
                    }
                }]
            }
        }
    });

    // chart by ontime vs overdue
    var ontime_vs_overdue = document.getElementById("ontime_vs_overdue");
    var myChart3 = new Chart(ontime_vs_overdue, {
        type: 'pie',
        data: {
            labels: [
                @isset($toodata) @php foreach ($toodata as $key => $value) { echo '"' . $value . " - " . $key . '"' . ', '; } @endphp @endisset
            ],
            datasets: [{
                data: [ @isset($too) {{ $too }} @endisset ],
                backgroundColor: [
                    window.chartColors.green,
                    window.chartColors.red,
                    window.chartColors.grey,
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            legend: {display: true,fullWidth: true,position: 'top',},
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];
                        var total = 0;
                        var label = tooltipLabel.split(" - ");
                        for (var i in allData) {total += allData[i];}
                        var tooltipPercentage = Math.round((tooltipData / total) * 100);
                        return label[1] + ': ' + label[0] + ' (' + tooltipPercentage + '%)';
                    }
                }
            },
        }
    });


    // chart by age group
    var overdue_period = document.getElementById("overdue_period");
    var mychart4 = new Chart(overdue_period, {
        type: 'radar',
        data: {
            labels: ['1-2 days', '3-5 days', '6-7 days', '8-14 days', 'Over 15 days'],
            datasets: [{
                label: 'Tasks',
                backgroundColor : "rgba(48, 164, 255, 0.2)",
                borderColor : "rgba(48, 164, 255, 0.8)",
                pointBackgroundColor : "rgba(48, 164, 255, 1)",
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(48, 164, 255, 1)",
                data: [ @isset($overdue_period) {{ $overdue_period }} @endisset ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            legend: {position: 'top',display: false,},
            title: {display: false,text: 'Radar'},
            scale: {ticks: {beginAtZero: true} }
        }
    });


    </script>
    @endsection 
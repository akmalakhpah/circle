@extends('layouts.personal')
    
    @section('content')
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">Organization Demographic
                    <button class="ui basic button mini offsettop5 float-right d-none d-sm-block" id="toggleview"><i class="ui icon columns"></i>View</button> 
                    <a href="{{ url('personal/reports') }}" class="ui blue basic button mini offsettop5 float-right"><i class="ui icon chevron left"></i>Return</a>
                </h2>
            </div>    
        </div>

        <div class="row columnview">
                
            <div class="col-md-6 colview">
                <div class="box box-aqua">
                    <div class="box-header with-border">
                        <h3 class="box-title">Company Population</h3>
                    </div>
                    <div class="box-body">
                        <div class="canvas-wrapper">
                            <canvas class="chart" id="company"></canvas>
                        </div>  
                    </div>
                </div>
            </div>

            <div class="colview col-md-6">
                <div class="box box-aqua">
                    <div class="box-header with-border">
                        <h3 class="box-title">Department Population</h3>
                    </div>
                    <div class="box-body">
                        <div class="canvas-wrapper">
                            <canvas class="chart" id="department"></canvas>
                        </div>  
                    </div>
                </div>
            </div>

            <div class="colview col-md-6">
                <div class="box box-aqua">
                    <div class="box-header with-border">
                        <h3 class="box-title">Gender Demographics</h3>
                    </div>
                    <div class="box-body">
                        <div class="canvas-wrapper">
                            <canvas class="chart" id="genderdiff"></canvas>
                        </div>  
                    </div>
                </div>
            </div>

            <div class="colview col-md-6">
                <div class="box box-aqua">
                    <div class="box-header with-border">
                        <h3 class="box-title">Age Demographics</h3>
                    </div>
                    <div class="box-body">
                        <div class="canvas-wrapper">
                            <canvas class="chart" id="age_group"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="colview col-md-6">
                <div class="box box-aqua">
                    <div class="box-header with-border">
                        <h3 class="box-title">Civil Status Demographics</h3>
                    </div>
                    <div class="box-body">
                        <div class="canvas-wrapper">
                            <canvas class="chart" id="civilstatus"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="colview col-md-6">
                <div class="box box-aqua">
                    <div class="box-header with-border">
                        <h3 class="box-title">Staff Hired by Year</h3>
                    </div>
                    <div class="box-body">
                        <div class="canvas-wrapper">
                            <canvas class="chart" id="yearhired"></canvas>
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
        grey: 'rgb(201, 203, 207)'
    };

    // chart by company
    var company = document.getElementById("company");
    var myChart0 = new Chart(company, {
        type: 'pie',
        data: {
            labels: [
                @isset($dcc) @php foreach ($dcc as $key => $value) { echo '"' . $key . ' ('. $value .')' . '"' . ', '; } @endphp @endisset
            ],
            datasets: [{
                data: [ @isset($cc) {{ $cc }} @endisset ],
                backgroundColor: [
                    window.chartColors.green,
                    window.chartColors.blue,
                    window.chartColors.purple,
                    window.chartColors.red,
                    window.chartColors.orange,
                    window.chartColors.yellow,
                    window.chartColors.gray,
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            legend: {display: true,fullWidth: true,position: 'right',},
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
                        return label[0] + ' (' + tooltipPercentage + '%)';
                    }
                }
            },
        }
    });

    // chart by department
    var department = document.getElementById("department");
    var myChart0 = new Chart(department, {
        type: 'pie',
        data: {
            labels: [
                @isset($dpc) @php foreach ($dpc as $key => $value) { echo '"' . $key . ' ('. $value .')' . '"' . ', '; } @endphp @endisset
            ],
            datasets: [{
                data: [ @isset($dc) {{ $dc }} @endisset ],
                backgroundColor: [
                    window.chartColors.red,
                    window.chartColors.orange,
                    window.chartColors.yellow,
                    window.chartColors.green,
                    window.chartColors.blue,
                    window.chartColors.purple,
                    window.chartColors.red,
                    window.chartColors.orange,
                    window.chartColors.yellow,
                    window.chartColors.green,
                    window.chartColors.blue,
                    window.chartColors.purple,
                    window.chartColors.red,
                    window.chartColors.orange,
                    window.chartColors.yellow,
                    window.chartColors.green,
                    window.chartColors.blue,
                    window.chartColors.purple],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            legend: {display: true,fullWidth: false,position: 'right',},
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
                        return label[0] + ' (' + tooltipPercentage + '%)';
                    }
                }
            },
        }
    });

    // chart by gender
    var genderdiff = document.getElementById("genderdiff");
    var myChart1 = new Chart(genderdiff, {
        type: 'pie',
        data: {
            labels: [
                @isset($dgc) @php foreach ($dgc as $key => $value) { echo '"' . $value . " - " . $key . '"' . ', '; } @endphp @endisset
            ],
            datasets: [{
                data: [ @isset($gc) {{ $gc }} @endisset ],
                backgroundColor: [
                    window.chartColors.blue,
                    window.chartColors.red,
                    window.chartColors.grey,
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            legend: {display: true,fullWidth: true,position: 'right',},
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
    var age_group = document.getElementById("age_group");
    var mychart2 = new Chart(age_group, {
        type: 'radar',
        data: {
            labels: ['Age 18-24', 'Age 25-31', 'Age 32-38', 'Age 39-45', 'Age 46+'],
            datasets: [{
                label: 'Headcount',
                backgroundColor : "rgba(48, 164, 255, 0.2)",
                borderColor : "rgba(48, 164, 255, 0.8)",
                pointBackgroundColor : "rgba(48, 164, 255, 1)",
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(48, 164, 255, 1)",
                data: [ @isset($age_group) {{ $age_group }} @endisset ],
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

    // chart by civilstatus
    var civilstatus = document.getElementById("civilstatus");
    var myChart3 = new Chart(civilstatus, {
        type: 'doughnut',
        data: {
            labels: [
                @isset($csc) @php foreach ($csc as $key => $value) { echo '"' . $value . " - " . $key . '"' . ', '; } @endphp @endisset
            ],
            datasets: [{
                data: [ @isset($cg) {{ $cg }} @endisset ],
                backgroundColor: [
                    window.chartColors.yellow,
                    window.chartColors.orange,
                    window.chartColors.red,
                    window.chartColors.purple,
                    window.chartColors.blue,
                    window.chartColors.green,
                    window.chartColors.gray,
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            legend: {display: true,fullWidth: true,position: 'right',},
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

    // chart by yearhired
    var yearhired = document.getElementById("yearhired");
    var myChart4 = new Chart(yearhired, {
        type: 'line',
        data: {
            labels: [ @isset($yhc) @php foreach ($yhc as $key => $value) { echo '"' . $key . '"' . ', '; } @endphp @endisset],
            datasets: [
            {
                label: '',
                backgroundColor:  "rgba(48, 164, 255, 0.2)",
                borderColor: "rgba(48, 164, 255, 0.8)",
                data: [ @isset($yc) {{ $yc }} @endisset ],
                fill: true,
            }]
        },
        options: {
            responsive: true,
            title: {display: false,text: 'Chart'},
            legend: {position: 'top',display: false,},
            tooltips: {mode: 'index',intersect: false,},
            hover: {mode: 'nearest',intersect: true},
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Year'
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

    </script>
    @endsection 
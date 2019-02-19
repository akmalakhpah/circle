<!doctype html>
<!--
* My Circle: Performance Management System
* Email: circle@aidan.my
* Version: 1.0
* Author: Akmal Akhpah
* Copyright 2019 Aidan Technologies
* Website: https://github.com/akmalakhpah/circle
-->
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta name="viewport" content="width=device-width" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>My Circle</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/bootstrap/css/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/semantic-ui/semantic.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/DataTables/datatables.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/style.css') }}">
        
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/assets/images/favicon/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/assets/images/favicon/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/assets/images/favicon/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/assets/images/favicon/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/assets/images/favicon/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/assets/images/favicon/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/assets/images/favicon/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/assets/images/favicon/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/assets/images/favicon/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('/assets/images/favicon/android-icon-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/assets/images/favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('/assets/images/favicon/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/assets/images/favicon/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('/assets/images/favicon/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('/assets/images/favicon/ms-icon-144x144.png') }}">
        <meta name="theme-color" content="#ffffff">        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="{{ asset('/assets/js/html5shiv.js') }}></script>
            <script src="{{ asset('/assets/js/respond.min.js') }}"></script>
        <![endif]-->

        @yield('styles')
    </head>
    <body>

        <div class="wrapper">
        
        <nav id="sidebar" class="active-">
            <div class="sidebar-header">
                <div class="logo">
                <a href="/" class="simple-text">
                    <img src="{{ asset('/assets/images/img/logo-small.png') }}">
                </a>
                </div>
            </div>

            <ul class="list-unstyled components">
                <li class="">
                    <a href="{{ url('personal/dashboard') }}">
                        <i class="ui icon sliders horizontal"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('personal/attendance/view') }}">
                        <i class="ui icon clock outline"></i>
                        <p>My Attendance</p>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('personal/schedules/view') }}">
                        <i class="ui icon calendar alternate outline"></i>
                        <p>My Schedule</p>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('personal/leaves/view') }}">
                        <i class="ui icon calendar plus outline"></i>
                        <p>My Leave</p>
                    </a>
                </li>
                <li>
                    <a href="{{ url('personal/settings') }}">
                        <i class="ui icon toggle off"></i>
                        <p>Settings</p>
                    </a>
                </li>
            </ul>
        </nav>

        <div id="body">
            <nav class="navbar navbar-expand-lg navbar-light bg-lightgray">
                <div class="container-fluid">

                    <button type="button" id="slidesidebar" class="ui icon button btn-light">
                        <i class="ui icon bars"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto navmenu">
                            <li class="nav-item">
                                <div class="ui pointing link dropdown item" tabindex="0">
                                    <i class="ui icon th"></i> <span class="navmenutext">Quick Access</span>
                                    <i class="dropdown icon"></i>
                                    <div class="menu" tabindex="-1">
                                      <a href="{{ url('clock') }}" target="_blank" class="item"><i class="ui icon clock"></i> Clock In/Out</a>
                                      <div class="divider"></div>
                                      <a href="{{ url('personal/profile/view') }}" target="_blank" class="item"><i class="ui icon user"></i> My Profile</a>
                                    </div>
                              </div>
                            </li>
                            <li class="nav-item">
                               <div class="ui pointing link dropdown item" tabindex="0">
                                    <i class="ui icon user outline"></i><span class="navmenutext">@isset(Auth::user()->name) {{ Auth::user()->name }} @endisset</span>
                                    <i class="dropdown icon"></i>
                                    <div class="menu" tabindex="-1">
                                      <a href="{{ url('personal/update-user') }}" class="item"><i class="ui icon user"></i> Update User</a>
                                      <a href="{{ url('personal/update-password') }}" class="item"><i class="ui icon lock"></i>  Change Password</a>
                                      <div class="divider"></div>
                                      <a href="{{ url('logout') }}" class="item"><i class="ui icon power"></i> Logout</a>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>

            <div class="content">
                @yield('content')
            </div>
            <input type="hidden" id="_url" value="{{url('/')}}">
        </div>
    </div>

    <script src="{{ asset('/assets/vendor/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/semantic-ui/semantic.min.js') }}"></script>
    <script src="{{ asset('/assets/js/bootstrap-notify.js') }}"></script>
    <script src="{{ asset('/assets/vendor/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/script.js') }}"></script>

    @if ($success = Session::get('success'))
    <script>
        $(document).ready(function() {
            $.notify({icon: 'ti-check',message: "{{ $success }}"},{type: 'success',timer: 600});
        });
    </script>
    @endif

    @if ($error = Session::get('error'))
    <script>
        $(document).ready(function() {
            $.notify({icon: 'ti-close',message: "{{ $error }}"},{type: 'danger',timer: 600});
        });
    </script>
    @endif

    @yield('scripts')

    </body>
</html>
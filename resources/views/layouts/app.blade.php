<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="id" lang="id">
<head>
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>
    <title>{{ $title ?? 'SaaS POS - Dashboard' }}</title>
    
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Theme Style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

    <!-- Font -->
    <link rel="stylesheet" href="{{ asset('font/fonts.css') }}">

    <!-- Icon -->
    <link rel="stylesheet" href="{{ asset('icon/style.css') }}">

    <!-- Favicon and Touch Icons -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('images/favicon.png') }}">

    @stack('head')
</head>

<body class="body">
    <!-- #wrapper -->
    <div id="wrapper">
        <!-- #page -->
        <div id="page" class="">
            <!-- layout-wrap -->
            <div class="layout-wrap">
                <!-- preload -->
                <div id="preload" class="preload-container">
                    <div class="preloading">
                        <span></span>
                    </div>
                </div>
                <!-- /preload -->

                <!-- Sidebar -->
                @include('layouts.sidebar')

                <!-- section-content-right -->
                <div class="section-content-right">
                    <!-- Navbar -->
                    @include('layouts.navbar')

                    <!-- main-content -->
                    <div class="main-content">
                        <!-- main-content-wrap -->
                        <div class="main-content-inner">
                            @yield('content')
                        </div>
                        <!-- /main-content-wrap -->
                    </div>
                    <!-- /main-content -->
                </div>
                <!-- /section-content-right -->
            </div>
            <!-- /layout-wrap -->
        </div>
        <!-- /#page -->
    </div>
    <!-- /#wrapper -->

    <!-- Javascript -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/apexcharts/line-chart-1.js') }}"></script>
    <script src="{{ asset('js/apexcharts/line-chart-2.js') }}"></script>
    <script src="{{ asset('js/apexcharts/line-chart-3.js') }}"></script>
    <script src="{{ asset('js/apexcharts/line-chart-4.js') }}"></script>
    <script src="{{ asset('js/apexcharts/line-chart-5.js') }}"></script>
    <script src="{{ asset('js/jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('js/jvectormap-us-lcc.js') }}"></script>
    <script src="{{ asset('js/jvectormap.js') }}"></script>
    <script src="{{ asset('js/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/zoom.js') }}"></script>
    <script src="{{ asset('js/theme-settings.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    @stack('scripts')
</body>
</html>
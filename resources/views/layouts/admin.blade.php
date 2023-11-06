@php
    $admin = \Illuminate\Support\Facades\Auth::user();
@endphp
    <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">
    <title>@yield('title')</title>
    <meta id="csrf" name="csrf-token" id="header-csrf-token" content="{{ csrf_token() }}">
    <link href="/assets/node_modules/morrisjs/morris.css" rel="stylesheet">
    <link href="/assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
    <link href="/dist/css/style.min.css" rel="stylesheet">
    <link href="/dist/css/pages/dashboard1.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="skin-red-dark fixed-layout">
<div id="main-wrapper">
    @include('snippets.admin.header')
    @include('snippets.admin.left_bar')
    <div class="page-wrapper">
        <div class="container-fluid">
            @yield('bredcrumb')
            @yield('content')
        </div>
    </div>
    @include('snippets.admin.footer')
</div>
<script src="/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
<script src="/assets/node_modules/popper/popper.min.js"></script>
<script src="/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/dist/js/perfect-scrollbar.jquery.min.js"></script>
<script src="/dist/js/waves.js"></script>
<script src="/dist/js/sidebarmenu.js"></script>
<script src="/dist/js/custom.min.js"></script>
<script src="/assets/node_modules/raphael/raphael-min.js"></script>
<script src="/assets/node_modules/morrisjs/morris.min.js"></script>
<script src="/assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
@yield('scripts')
@yield('modals')
</body>

</html>

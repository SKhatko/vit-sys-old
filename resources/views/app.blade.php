<!DOCTYPE html>
<html lang="{{ \App\Config::$language }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title or "VITisch" }}</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,500,700" rel="stylesheet">

    {{--    <link href="{{ asset('/css/admin.css?v=3.2') }}" rel="stylesheet">--}}

    {{--<link rel="stylesheet" type="text/css" media="print" href="{{ asset('/css/print.css') }}"/>--}}

    <link href="{{ mix('/css/main.css') }}" rel="stylesheet">

    <link rel="shortcut icon" type="image/png" href="{{ url('favicon.png') }}"/>
    {{--<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->--}}
<!-- WARNING: Respond.js does not work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('head')

</head>
<body class="{{ session()->has('flash_message') ? 'modal-open' : '' }}">

<div hidden>
    <!-- Link sprite of icons -->
    {!! file_get_contents(asset('images/icons.svg')) !!}
</div>

<div class="app">

    <!-- Yield main layout -->
@yield('layout')

<!-- Include alert mesages to all pages -->
@include('components.alert')

<!-- TODO remove -->
{{--@include('partials.errors')--}}

<!-- All modals and other included stuff is here -->
    @yield('helpers')

</div>

<!-- Main script linking -->
<script src="{{ mix('js/app.js') }}"></script>

<!-- Script initialisations -->
@yield('script')

</body>
</html>

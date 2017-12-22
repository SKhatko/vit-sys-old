<!DOCTYPE html>
<html>
<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    <meta http-equiv="X-Frame-Options" content="allow">

    <link rel="shortcut icon" type="image/png" href="{{ url('favicon.png') }}"/>

    <title>{{ \App\Config::$restaurant_name.' - '.trans('menu.online_menu') }}</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <link href="{{ asset('css/menu.css?v=1.6') }}" rel="stylesheet">

    {!! \App\Misc::importGoogleFonts($settings) !!}

    @yield('style')
</head>

<body>
@yield('body')
</body>
</html>
<!DOCTYPE html>
<html>
<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    <meta http-equiv="X-Frame-Options" content="allow">

    <title>{{ trans('reception.online_reservation') }} - {{ \App\Config::$restaurant_name }}</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <link rel="shortcut icon" type="image/png" href="{{ url('favicon.png') }}"/>

    @yield('head')

    <link href="{{ asset('css/online.css?v=1.1') }}" rel="stylesheet">
</head>
<body>
<header>
    @yield('header')
</header>

@yield('steps-section')

<section class="content">

    <div class="container">
        <div class="row">
            <div class="col-md-12 mtop">
                @include('partials.session_flash')

            </div>
        </div>
    </div>


    @yield('content')
</section>

<section class="vitisch-section">
    <div class="container">
        <div class="row">
            <div class="signature calign">
                <div style="width:150px; margin:0 auto; position:relative;" class="calign" id="logo-holder">
                    <p style="position:absolute; top:0px; left:50px;">{{ trans('online.powered_by') }}</p>
                    <img src="{{ asset('img/vitisch-logo.png') }}" alt="VITisch" style="width:145px;"/>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="{{ asset('js/language-switcher.js') }}"></script>
@yield('scripts')
</body>
</html>
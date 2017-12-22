<!DOCTYPE html>
<html>
<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    <meta http-equiv="X-Frame-Options" content="allow">

    <title>{{ trans('online.online_preorder') }} - {{ \App\Config::$restaurant_name }}</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <link rel="shortcut icon" type="image/png" href="{{ url('favicon.png') }}"/>

    @yield('head')

    <link href="{{ asset('css/online.css?v=2') }}" rel="stylesheet">
</head>
<body>
<header>
    <div class="container">
        <div class="row">
            <div class="col-md-12 calign">
                @if (isset($view) && $view == 'menu')
                    @include('online.menu._language_form')
                @else
                    <div id="language-select">
                        {!! Form::open(['method' => 'post', 'action' => 'Online\OnlineController@setLanguage']) !!}
                        <form action="server-side-script.php">
                            <select id="language-options" name="language">
                                @foreach ($languages as $key => $value)
                                    <option value="{{ $key }}"
                                            title="{{ action('Online\OnlineController@setLanguage', [$key]) }}"
                                            {!! ($key == $language) ? 'selected="selected"' : '' !!}>{{ $value }}</option>
                                @endforeach
                            </select>

                            <input value="Select" type="submit"/>
                        {!! Form::close() !!}
                    </div>
                @endif


                @if (isset($view) && ($view == 'confirm' || $view == 'menu' || $view == 'delete'))
                    <a href="{{ $backPath }}" class="block-back-link"><i
                                class="fa fa-angle-left"></i> {{ trans('online.back') }} </a>
                @endif

                <h1 class="calign">{{ \App\Config::$restaurant_name }} - {{ trans('online.online_preorder') }}</h1>
            </div>
        </div>
    </div>
</header>

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @include('partials.session_flash')
                @include('partials.errors')

                @if (isset($outdated) && $outdated)
                    <div class="alert alert-warning mtop">
                        {{ trans('online.preorders_are_locked', ['hours_limit' => isset($hoursLimit) ? $hoursLimit : NULL]) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @yield('content')
</section>

<section class="vitisch-section mtop">
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
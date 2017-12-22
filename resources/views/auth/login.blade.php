@extends('app')

@section('layout')

    <div class="auth">
        <div class="auth-block">
            <div class="auth-tenant">
                <div class="auth-tenant__name">
                    {{ \App\Config::$restaurant_name }}
                </div>
            </div>

            <div class="auth-logo">
                <a href="http://vitisch.com" target="_blank" class="auth-logo__link">
                    <svg class="auth-logo__link--icon">
                        <use xlink:href="#icon-logo"></use>
                    </svg>
                </a>
            </div>

            <div class="auth-form">

                <div class="auth-form__info">
                    Please log in to your account
                    {{--                        {{ trans('auth.log_in_welcome_msg') }}--}}
                </div>

                {{ Form::open(['method' => 'POST', 'action' => 'Auth\LoginController@login']) }}

                <div class="space-bottom">

                    {{ Form::label('email', trans('auth.email'), ['class' => 'auth-form__label']) }}
                    {{ Form::email('email', old('email'), ['class' => 'auth-form__input js-auth-form__input', 'placeholder' => 'Name@mail.com', 'required']) }}

                </div>

                <div class="space-bottom">

                    {{ Form::label('password', trans('auth.password'), ['class' => 'auth-form__label']) }}
                    {{ Form::password('password', ['placeholder' => '*********', 'class' => 'auth-form__input js-auth-form__input', 'required']) }}

                </div>

                <div class="space-bottom">

                    <div class="auth-form__checkbox">
                        {{ Form::checkbox('remember', old('remember'), true, ['id' => 'remember-me']) }}
                        <label for="remember-me"></label>
                    </div>

                    {{ Form::label('remember-me',  'Remember Password', ['class' => 'auth-form__checkbox--label']) }}

                    @include('components.validation')

                </div>

                {{ Form::submit( 'Log In', ['class' => 'auth-form__submit']) }}

                {{ Form::close() }}

                <div class="auth-form-contact">
                    <a href="mailto:vitisch@vitisch.com" class="auth-form-contact__link">
                        <svg class="auth-form-contact__link--icon">
                            <use xlink:href="#icon-envelop"></use>
                        </svg>

                        VITisch@vitisch.com
                    </a>
                </div>

            </div>
        </div>
    </div>

@stop

@section('script')
    <script>
        new Auth();
    </script>

@stop

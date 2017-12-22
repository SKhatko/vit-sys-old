@extends('app')

@section('layout')

    <!-- Include header -->
    @include('layout/header')

    <main class="main">

        <!-- Include content -->
        <section class="content">

            <div class="pin">

                <div class="pin-form">
                    {{ Form::open(['method' => 'POST', 'action' => 'Auth\PinController@postLogin']) }}

                    <div class="pin-form--title">
                        {{ trans('auth.pin_authorization') }}
                    </div>

                    <div class="pin-form--mesage">
                        {{ trans('auth.pin_authorization_required_to_access_interface') }}
                    </div>

                    {{ Form::label('pin', trans('auth.pin_code'), ['class' => 'label']) }}
                    {{ Form::password('pin', ['class' => 'input', 'autofocus', 'required', 'autocomplete' => 'off']) }}
                    {{ Form::button(trans('general.submit'), ['type' => 'submit', 'class' => 'pin-form--submit']) }}

                    {{ Form::close() }}
                </div>
            </div>

        </section>

    </main>

@stop

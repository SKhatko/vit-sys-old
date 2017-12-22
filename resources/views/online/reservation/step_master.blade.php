@extends('online.reservation.master')

@section('head')
    @yield('head')
@stop

@section('header')
    <div class="container">
        <div class="row">
            <div class="col-md-12 calign">
                <div id="language-select">
                    {!! Form::open(['method' => 'post', 'action' => 'Online\OnlineController@setLanguage']) !!}
                    {{--<form action="server-side-script.php">--}}
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

                <h1 class="calign">{{ \App\Config::$restaurant_name }}
                    - {{ trans('reception.online_reservation') }}</h1>
            </div>
        </div>
    </div>
@stop

@section('steps-section')

    <section class="steps-section">
        <div class="container">
            <div class="row">

                <div class="col-md-4 col-sm-4 col-xs-4 calign">
                    <a href="{{ action('Online\ReservationsController@step1') }}">
                        <div class="step {{ $step == 1 ? 'active' : 'available' }}" id="step-1">
                            <img src="{{ asset('img/online/calendar-icon.png') }}" alt="Reservation Info"/>
                            <span class="step-title">{{ trans('general.step_with_number', ['number' => 1]) }}</span>
                            <span class="step-content">{{ trans('reception.reservation_info') }}</span>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 col-sm-4 col-xs-4 calign">

                    <?php $class = ''; ?>
                    @if ($step == 2)
                        <?php $class = 'active'; ?>
                    @elseif ($step > 2)
                        <?php $class = 'available'; ?>
                    @endif

                    <a href="{{ ($step >= 2) ? action('Online\ReservationsController@step2') : 'javascript:;' }}">
                        <div class="step {{ $class }}" id="step-2">
                            <img src="{{  asset('img/online/user-profile-icon.png') }}" alt="Client Profile"/>
                            <span class="step-title">{{ trans('general.step_with_number', ['number' => 2]) }}</span>
                            <span class="step-content">{{ trans('crm.client_profile') }}</span>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 col-sm-4 col-xs-4 calign">

                    <?php $class = ''; ?>
                    @if ($step == 3)
                        <?php $class = 'active'; ?>
                    @elseif ($step > 3)
                        <?php $class = 'available'; ?>
                    @endif

                    <a href="{{ $step >= 3 ? action('Online\ReservationsController@step3') : 'javascript:;' }}">
                        <div class="step {{ $class }}" id="step-3">
                            <img src="{{ asset('img/online/confirmation-icon.png') }}" alt="Confirmation"/>
                            <span class="step-title">{{ trans('general.step_with_number', ['number' => 3]) }}</span>
                            <span class="step-content">{{ trans('general.confirmation') }}</span>
                        </div>
                    </a>
                </div>


            </div>
        </div>
    </section>
@stop

@section('content')
    @yield('content')
@stop

@section('scripts')
    @yield('scripts')
@stop
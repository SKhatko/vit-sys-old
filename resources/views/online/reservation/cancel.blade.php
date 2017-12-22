@extends('online.reservation.master')

@section('header')
    <div class="container">
        <div class="row">
            <div class="col-md-12 calign">
                <h1 class="calign">{{ \App\Config::$restaurant_name }}
                    - {{ trans('reception.cancel_reservation') }}</h1>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mboth">

                @if ($error)
                    <p class="alert alert-danger">
                        @if ($reason == 'already_cancelled')
                            {!! trans('online.cancel_already_cancelled_msg') !!}
                        @elseif ($reason == 'invalid_parameters')
                            {!! trans('online.cancel_invalid_parameters_msg') !!}
                        @elseif ($reason == 'too_late')
                            {!! trans('online.cancel_too_late_msg') !!}
                        @endif
                    </p>
                @else
                    @if ($complete)
                        <p class="alert alert-success">
                            {!! trans('online.cancel_success_msg') !!}
                        </p>
                    @else
                        <p class="alert alert-warning">
                            {!! trans('online.cancel_confirmation_msg') !!}
                        </p>

                        {!! Form::open(['action' => 'Online\ReservationsController@postCancelReservation', 'method' => 'post']) !!}
                        <input type="hidden" name="reference_id" value="{{ $referenceId }}"/>
                        <input type="hidden" name="cancel_token" value="{{ $cancelToken }}"/>

                        {!! Form::submit(trans('reception.cancel_reservation'), ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    @endif
                @endif

            </div>
        </div>
    </div>
@stop
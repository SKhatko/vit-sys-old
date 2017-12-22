@extends('online.reservation.master')

@section('header')
    <div class="container">
        <div class="row">
            <div class="col-md-12 calign">
                <h1 class="calign">{{ \App\Config::$restaurant_name }}
                    - {{ trans('reception.online_reservation') }}</h1>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12 mtop">
                <p class="alert alert-danger">
                    {{ trans('online.duplicate_reservation_msg') }}
                </p>
            </div>
        </div>
    </div>
@stop
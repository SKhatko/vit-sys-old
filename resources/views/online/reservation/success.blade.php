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
                <p class="alert alert-success">
                    {{ trans('online.reservation_success_msg') }}.

                    <br>

                    {!! trans('online.reference_id_is', ['reference_id' => '<strong>'.strtoupper(Session::get('online.reservation_identifier')).'</strong>']) !!}

                    <br>

                    {{ trans('online.thank_you') }}
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 mboth">
                <div class="content-box">
                    <h2>{{ trans('reception.reservation_info') }}</h2>

                    <p>
                        {{ Session::get('online.persons_num') }} {{ trans('general.persons') }}
                        <br>
                        {{ Session::get('online.date') }}
                        <br>
                        {{ Session::get('online.time') }} {{ trans('online.time_suffix') }}

                    </p>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12 mboth">
                <div class="content-box">
                    <h2>{{ trans('crm.client_info') }}</h2>

                    <p>
                        {{ trans('crm.honorific_'.Session::get('online.honorific')) }} {{ Session::get('online.first_name') }} {{ Session::get('online.last_name') }}
                        <br>

                        {{ Session::get('online.email') }}
                        <br>
                        {{ Session::get('online.mobile') }}
                        {{ (Session::has('online.phone_num') && Session::get('online.phone_num')) ? ' / '.Session::get('online.phone_num') : '' }}
                    </p>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 calign mbottom">
                <a href="{{ action('Online\ReservationsController@clearSession') }}"
                   class="btn btn-warning">{{ trans('online.make_another_reservation') }}</a>
            </div>
        </div>

    </div><!-- /.container -->
@stop
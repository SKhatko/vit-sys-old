@extends('online.reservation.step_master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 mtop">
                <div class="content-box">
                    <h2>{{ trans('reception.reservation_info') }}</h2>

                    <p>
                        {{ Session::get('online.persons_num') }} {{ trans('general.persons') }}
                        <br>
                        {{ Session::get('online.date') }}
                        <br>
                        {{ Session::get('online.time') }}

                    </p>

                    <div class="form-group">
                        <a href="{{ action('Online\ReservationsController@step1') }}"
                           class="btn btn-warning">{{ trans('general.edit') }}</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12 mtop">
                <div class="content-box">
                    <h2>{{ trans('crm.client_info') }}</h2>

                    <p>
                        {{ trans('crm.honorific_'.Session::get('online.honorific')) }}
                        . {{ Session::get('online.first_name') }} {{ Session::get('online.last_name') }}
                        <br>

                        {{ Session::get('online.email') }}
                        <br>
                        {{ Session::get('online.mobile') }}
                        {{ (Session::has('online.phone_num') && Session::get('online.phone_num')) ? ' / '.Session::get('online.phone_num') : '' }}
                    </p>

                    <div class="form-group">
                        <a href="{{ action('Online\ReservationsController@step2') }}"
                           class="btn btn-warning">{{ trans('general.edit') }}</a>
                    </div>
                </div>
            </div>
        </div>

        @if (Session::has('online.notes') && Session::get('online.notes'))
            <div class="row">
                <div class="col-md-12 mtop">
                    <div class="content-box">
                        <h2>{{ trans('reception.note') }}</h2>

                        <p>
                            {{ Session::get('online.notes') }}
                        </p>

                        <div class="form-group">
                            <a href="{{ action('Online\ReservationsController@step2') }}"
                               class="btn btn-warning">{{ trans('general.edit') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12 mboth">
                <div class="content-box">
                    <h2>{{ trans('reception.place_reservation') }}</h2>

                    {!! Form::open(['method' => 'post', 'action' => 'Online\ReservationsController@postStep3', 'id' => 'submit-form']) !!}

                    <div class="form-group">

                        <input type="checkbox" value="1" name="restaurant_newsletter" checked/>
                        {!! trans('online.restaurant_newsletter_checkbox_msg_with_name', ['name' => \App\Config::$restaurant_name]) !!}
                        <br>

                        <input type="checkbox" value="1" name="vitisch_newsletter"
                               checked/> {{ trans('online.vitisch_newsletter_checkbox_msg') }}
                        <br>
                        <br>


                        <strong>{!! trans('online.agreement_msg', ['link_open' => '<a href="'.action('Online\OnlineController@terms').'" target="_blank">', 'link_close' => '</a>']) !!}
                        </strong>


                    </div>

                    <div class="form-group">
                        {!! Form::submit(trans('general.submit'), ['class' => 'btn btn-primary', 'id' => 'submit-btn']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $("#submit-form").submit(function () {
            $("#submit-btn").prop('disabled', true);
        });
    </script>
@stop
@extends('app')

@section('layout')

    <div class="col-md-12">
        <p>
            {{ trans('general.contact_us_msg') }}
        </p>

        <label>{{ trans('general.office') }}:</label> Merowingerplatz 1a, 40225 Düsseldorf<br>
        <label>{{ trans('general.email') }}:</label>
        <a href="mailto:{{ Config::get('app.vitisch_email') }}">{{ Config::get('app.vitisch_email') }}</a><br>
        <label>{{ trans('general.phone') }}:</label> {{ Config::get('app.vitisch_phone') }}<br>
    </div>
@stop
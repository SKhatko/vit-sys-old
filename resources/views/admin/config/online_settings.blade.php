@extends('admin.index')

@section('content')

    <div class="admin-online">
        {!! Form::open(['method' => 'post', 'action' => 'ConfigController@postOnline']) !!}

        <div class="admin-online__header">
            <div class="admin-online__header-input">
                {!! Form::label('max_online_persons', trans('admin.max_reservation_persons'), ['class' => 'label']) !!}
                {!! Form::input('number', 'max_online_persons', $config::$max_online_persons, ['class' => 'input', 'required', 'min' => 0 ]) !!}
            </div>
            <div class="admin-online__header-input">
                {!! Form::label('max_persons_per_hour', trans('admin.max_persons_per_hour'), ['class' => 'label']) !!}
                {!! Form::input('number', 'max_persons_per_hour', $config::$max_persons_per_hour, ['class' => 'input', 'required', 'min' => 0]) !!}
            </div>
            <div class="admin-online__header-input">
                {!! Form::label('max_hours_before_reservation_allowed', trans('admin.max_hours_before_reservation_allowed'), ['class' => 'label']) !!}
                {!! Form::input('number', 'max_hours_before_reservation_allowed', $config::$max_hours_before_reservation_allowed, ['class' => 'input', 'required', 'min' => 0, 'max' => 48]) !!}
            </div>
        </div>

        {{--                    {!! Form::label('max_reservations_per_hour', trans('admin.max_reservations_per_hour').' *') !!}--}}
        {{--                    {!! Form::input('number', 'max_reservations_per_hour', $config::$max_reservations_per_hour, ['class' => 'form-control', 'required']) !!}--}}

        <div class="admin-online__welcome">
            {!! Form::label('welcome_message', trans('admin.online_restaurant_message'), ['class' => 'label']) !!}
            {!! Form::textarea('welcome_message', $config::$welcome_message, ['class' => 'admin-online__welcome--textarea']) !!}
        </div>

        <div class="admin-online__footer">
            <div class="admin-online__footer-link">
                <div class="admin-online__footer-link-input">
                    {!! Form::label('online_url', trans('admin.online_reservation_url'), ['class' => 'label']) !!}
                    <input type="text" name="online_url" value="{{ $config::$online_reservation_url }}" class="input js-admin-online__footer-link-input" readonly>

                </div>
                {{ Form::button('Copy Link', ['class' => 'admin-online__footer--copy js-admin-online__footer--copy']) }}
            </div>

            {!! Form::button(trans('general.submit'), ['type'=>'submit', 'class' => 'admin-online__footer--submit']) !!}
        </div>

        {!! Form::close() !!}

    </div>

@stop

@section('script')
    <script>
        new Admin().onlineSettings()
    </script>
@stop
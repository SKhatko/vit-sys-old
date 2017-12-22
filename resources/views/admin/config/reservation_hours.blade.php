@extends('admin.index')

@section('content')

    <div class="admin-hours js-admin-hours">

        {!! Form::open(['method' => 'post', 'action', 'ConfigController@postReservationHours']) !!}

        <div class="admin-hours__basic">
            <div class="admin-hours__basic-shift">
                {!! Form::label('day_start', trans('admin.day_start'), ['class' => 'label']) !!}
                {!! Form::text('day_start', $config::$day_start, ['class' => 'input js-admin-hours__basic-shift--morning', 'required', 'autocomplete' => 'off']) !!}
            </div>
            <div class="admin-hours__basic-shift">
                {!! Form::label('day_end', trans('admin.night_start'), ['class' => 'label']) !!}
                {!! Form::text('day_end', $config::$day_end, ['class' => 'input js-admin-hours__basic-shift--day', 'required', 'autocomplete' => 'off']) !!}
            </div>
            <div class="admin-hours__basic-shift">
                {!! Form::label('night_end', trans('admin.night_end'), ['class' => 'label']) !!}
                {!! Form::text('night_end', $config::$night_end, ['class' => 'input js-admin-hours__basic-shift--night', 'required', 'autocomplete' => 'off']) !!}
            </div>

            {!! Form::button(trans('general.submit'), ['type'=>'submit', 'class' => 'admin-hours__basic--submit']) !!}

        </div>

        <div class="admin-hours__daily">

            <div class="admin-hours__daily-days">
                @foreach($dailySchedule as $dayName => $daySchedule)
                    <div class="admin-hours__daily-days-day js-admin-hours__daily-days-day" data-day="{{ $dayName }}">
                        <div class="admin-hours__daily-days-day--name js-admin-hours__daily-days-day--name">
                            {{ trans('dates.' . $dayName ) }}
                        </div>

                        <label class="admin-hours__daily-days-day--switch">
                            {{ Form::checkbox($dayName, true, $daySchedule['enabled'], ['class' => 'js-admin-hours__daily-days-day--switch']) }}
                            <i data-on="On" data-off="Off"></i>
                            <span></span>
                        </label>

                    </div>
                @endforeach
            </div>

            <div class="admin-hours__daily-times">
                <div class="admin-hours__daily-times--header">
                    {{ trans('reception.day_shift') }}
                </div>
                <div class="admin-hours__daily-times-content">

                    @foreach($dailySchedule as $dayName => $daySchedule)

                        <div class="admin-hours__daily-times-items js-admin-hours__daily-times-items"
                             data-day="{{ $dayName }}">

                            <div class="admin-hours__daily-times-item">
                                <div class="admin-hours__daily-times-item--checkbox">
                                    {{ Form::checkbox( $dayName . '_day',true, false,['class' => 'js-admin-hours__daily-times-all--checkbox', 'id' => $dayName . '_day']) }}
                                    <label for="{{  $dayName . '_day' }}"></label>
                                </div>
                                {{ Form::label($dayName . '_day', trans('general.select_all') , ['class' => 'admin-hours__daily-times-item--label']) }}
                            </div>

                            @for ($i=$dayStartMinutes; $i < $dayEndMinutes; $i+=$interval)

                                <div class="admin-hours__daily-times-item">
                                    <div class="admin-hours__daily-times-item--checkbox">
                                        {{ Form::checkbox($dayName . '_times[]',$i,in_array($i, $daySchedule['schedule']),['class' => 'js-admin-hours__daily-times-item--checkbox','id' => $dayName . '_' . $i]) }}
                                        <label for="{{ $dayName . '_' . $i }}"></label>
                                    </div>

                                    {{ Form::label($dayName . '_' . $i, \App\ScheduleSingleton::formatMinutes($i) , ['class' => 'admin-hours__daily-times-item--label']) }}

                                </div>

                            @endfor
                        </div>
                    @endforeach


                </div>
            </div>

            <div class="admin-hours__daily-times">
                <div class="admin-hours__daily-times--header">
                    {{ trans('reception.night_shift') }}
                </div>
                <div class="admin-hours__daily-times-content">

                    @foreach($dailySchedule as $dayName => $daySchedule)

                        <div class="admin-hours__daily-times-items js-admin-hours__daily-times-items"
                             data-day="{{ $dayName }}">

                            <div class="admin-hours__daily-times-item">
                                <div class="admin-hours__daily-times-item--checkbox">
                                    {{ Form::checkbox( $dayName . '_night',true, false,['class' => 'js-admin-hours__daily-times-all--checkbox', 'id' => $dayName . '_night']) }}
                                    <label for="{{  $dayName . '_night' }}"></label>
                                </div>
                                {{ Form::label($dayName . '_night', trans('general.select_all') , ['class' => 'admin-hours__daily-times-item--label']) }}
                            </div>

                            @for ($i = $dayEndMinutes; $i < $nightEndMinutes; $i+=$interval)
                                <div class="admin-hours__daily-times-item">
                                    <div class="admin-hours__daily-times-item--checkbox">
                                        {{ Form::checkbox($dayName . '_times[]',$i,in_array($i, $daySchedule['schedule']),['class' => 'js-admin-hours__daily-times-item--checkbox', 'id' => $dayName . '_' . $i]) }}
                                        <label for="{{ $dayName . '_' . $i }}"></label>
                                    </div>

                                    {{ Form::label($dayName . '_' . $i, \App\ScheduleSingleton::formatMinutes($i) , ['class' => 'admin-hours__daily-times-item--label']) }}

                                </div>
                            @endfor
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="admin-hours__daily-bottom">
            {!! Form::button(trans('general.submit'), ['type' => 'submit', 'class' => 'admin-hours__daily-bottom--submit']) !!}
        </div>


        {!! Form::close() !!}

        <div class="admin-hours__custom">

            {!! Form::open(['method' => 'post', 'action' => 'OffdaysController@store']) !!}

            <div class="admin-hours__custom-top">

                <div class="admin-hours__custom-top-date">
                    {{ Form::label('date', trans('general.date'), ['class' => 'label']) }}
                    {{ Form::text('date', null, ['class' => 'input js-admin-hours__custom-top--date', 'required']) }}
                </div>

                <div class="admin-hours__custom-top-shift">
                    {!! Form::label('shift', trans('general.time_of_day'), ['class' => 'label']) !!}
                    {{ Form::select('shift', $shifts, null, ['class' => 'admin-hours__custom-top-shift--select js-admin-hours__custom-top-shift--select', 'required'] ) }}
                </div>

                <div class="admin-hours__custom-top-status">
                    {!! Form::label('enabled', trans('general.status'), ['class' => 'label']) !!}

                    <label class="admin-hours__custom-top-status--switch">
                        {{ Form::checkbox('enabled', true, true) }}
                        <i data-on="On" data-off="Off"></i>
                        <span></span>
                    </label>

                </div>

                <div class="admin-hours__custom-top-time">
                    {!! Form::label('time', 'Hours', ['class' => 'label']) !!}

                    <div class="admin-hours__custom-top-time-dropdown js-admin-hours__custom-top-time-dropdown">
                        <button class="admin-hours__custom-top-time-dropdown--button js-admin-hours__custom-top-time-dropdown--button"
                                type="button">
                            {{ trans('general.time') }}
                        </button>

                        <div class="admin-hours__custom-top-time-dropdown-menu">
                            <div data-custom-time="day"
                                 class="admin-hours__custom-top-time-dropdown-shift js-admin-hours__custom-top-time-dropdown-shift">

                                <div class="admin-hours__custom-top-time-dropdown-menu-item">
                                    <div class="admin-hours__custom-top-time-dropdown-menu-item--checkbox">
                                        {{ Form::checkbox( 'select_all_day',true, false,['class' => 'js-admin-hours__custom-top-time-dropdown-menu-item--all-checkbox', 'id' => 'select_all_day']) }}
                                        <label for="{{  'select_all_day' }}"></label>
                                    </div>
                                    {{ Form::label('select_all_day', trans('general.select_all') , ['class' => 'admin-hours__custom-top-time-dropdown-menu-item--label']) }}
                                </div>

                                @for ($i=$dayStartMinutes; $i < $dayEndMinutes; $i+=$interval)
                                    <div class="admin-hours__custom-top-time-dropdown-menu-item">
                                <span class="admin-hours__custom-top-time-dropdown-menu-item--checkbox">
                                    {{ Form::checkbox('times[]', $i, false,['class' => 'js-admin-hours__custom-top-time-dropdown-menu-item--checkbox','id' => $i]) }}
                                    <label for="{{ $i }}"></label>
                                </span>

                                        {{ Form::label($i, \App\ScheduleSingleton::formatMinutes($i) , ['class' => 'admin-hours__custom-top-time-dropdown-menu-item--label']) }}
                                    </div>
                                @endfor
                            </div>

                            <div data-custom-time="night"
                                 class="admin-hours__custom-top-time-dropdown-shift js-admin-hours__custom-top-time-dropdown-shift">

                                <div class="admin-hours__custom-top-time-dropdown-menu-item">
                                    <div class="admin-hours__custom-top-time-dropdown-menu-item--checkbox">
                                        {{ Form::checkbox( 'select_all_night',true, false,['class' => 'js-admin-hours__custom-top-time-dropdown-menu-item--all-checkbox', 'id' => 'select_all_night']) }}
                                        <label for="select_all_night"></label>
                                    </div>
                                    {{ Form::label('select_all_night', trans('general.select_all') , ['class' => 'admin-hours__custom-top-time-dropdown-menu-item--label']) }}
                                </div>

                                @for ($i = $dayEndMinutes; $i < $nightEndMinutes; $i+=$interval)
                                    <div class="admin-hours__custom-top-time-dropdown-menu-item">
                                <span class="admin-hours__custom-top-time-dropdown-menu-item--checkbox">
                                    {{ Form::checkbox('times[]', $i, false,['class' => 'js-admin-hours__custom-top-time-dropdown-menu-item--checkbox','id' => $i]) }}
                                    <label for="{{ $i }}"></label>
                                </span>

                                        {{ Form::label($i, \App\ScheduleSingleton::formatMinutes($i) , ['class' => 'admin-hours__custom-top-time-dropdown-menu-item--label']) }}
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <div class="admin-hours__custom-top-reason">
                    {!! Form::label('reason_for_change', trans('admin.reason_for_change'), ['class' => 'label']) !!}
                    {!! Form::text('reason_for_change', NULL, ['class' => 'input', 'required']) !!}
                </div>

                <div class="admin-hours__custom-top-create">
                    {!! Form::submit(trans('general.create'), ['class' => 'admin-hours__custom-top-create--submit']) !!}
                </div>
            </div>

            {!! Form::close() !!}

            @if (count($offdays))

                <div class="admin-hours__custom-table">
                    <div class="admin-hours__custom-table-header">

                        <div class="admin-hours__custom-table-header--date">
                            {{ trans('general.date') }}
                        </div>

                        <div class="admin-hours__custom-table-header--day">
                            {{ trans('general.week_day') }}
                        </div>

                        <div class="admin-hours__custom-table-header--time">
                            {{ trans('general.time_of_day') }}
                        </div>

                        <div class="admin-hours__custom-table-header--status">
                            {{ trans('general.status') }}
                        </div>

                        <div class="admin-hours__custom-table-header--reason">
                            {{ trans('admin.reason_for_change') }}
                        </div>

                        <div class="admin-hours__custom-table-header--edit">
                            {{ trans('general.edit') }}
                        </div>

                        <div class="admin-hours__custom-table-header--delete">
                            {{ trans('general.delete') }}
                        </div>

                    </div>

                    <div class="admin-hours__custom-table-content">

                        @foreach ($offdays as $offday)

                            <div class="admin-hours__custom-table-content-item js-admin-hours__custom-table-content-item"
                                 data-offday-id="{{ $offday->id }}">

                                <div class="admin-hours__custom-table-content--date js-admin-hours__custom-table-content--date">
                                    {{ date("d.m.Y", strtotime($offday->date)) }}
                                </div>

                                <div class="admin-hours__custom-table-content--day">
                                    {{ trans('general.'.strtolower(date("l", strtotime($offday->date)))) }}
                                </div>

                                <div class="admin-hours__custom-table-content--time js-admin-hours__custom-table-content--time">
                                    {{ trans('general.'.$offday->shift) }}
                                </div>

                                <div class="admin-hours__custom-table-content--status">
                                    {!! $offday->enabled ? trans('general.enabled') : trans('general.disabled') !!}
                                </div>

                                <div class="admin-hours__custom-table-content--reason"
                                     title="{{ $offday->reason_for_change }}">
                                    {{ $offday->reason_for_change }}
                                </div>

                                <div class="admin-hours__custom-table-content--edit">
                                    <svg class="admin-hours__custom-table-content--edit-icon js-admin-hours__custom-table-content--edit">
                                        <use xlink:href="#icon-edit"></use>
                                    </svg>
                                </div>

                                <div class="admin-hours__custom-table-content--delete">
                                    <svg class="admin-hours__custom-table-content--delete-icon js-admin-hours__custom-table-content--delete">
                                        <use xlink:href="#icon-cross"></use>
                                    </svg>
                                </div>

                            </div>

                        @endforeach

                    </div>
                </div>

                {!! $offdays->links() !!}

            @endif


        </div>

    </div>
@stop

@section('helpers')
    <!----- edit ITEM MODAL ------>
    @component('components.modal', ['class' => 'admin-hours__modal-edit js-admin-hours__modal-edit'])

        {{ Form::open(['method' => 'PATCH', 'action' => 'OffdaysController@index']) }}

        <div class="admin-hours__modal-edit--title">
            {{ trans('admin.edit_offday_with_date') }}
        </div>

        <div class="admin-hours__modal-edit-top">
            <div class="admin-hours__modal-edit-top--date js-admin-hours__modal-edit-top--date"></div>

            <div class="admin-hours__modal-edit-top--shift js-admin-hours__modal-edit-top--shift"></div>
        </div>

        <div class="admin-hours__modal-edit-status">
            {!! Form::label('enabled', trans('general.status'), ['class' => 'label']) !!}

            <label class="admin-hours__modal-edit-status--switch">
                {{ Form::checkbox('enabled', true, true, ['class' => 'js-admin-hours__modal-edit-status--switch']) }}
                <i data-on="On" data-off="Off"></i>
                <span></span>
            </label>
        </div>

        <div class="admin-hours__modal-edit-time">
            {!! Form::label('time', 'Hours', ['class' => 'label']) !!}

            <div class="admin-hours__modal-edit-time-dropdown js-admin-hours__modal-edit-time-dropdown">
                <button class="admin-hours__modal-edit-time-dropdown--button js-admin-hours__modal-edit-time-dropdown--button"
                        type="button">
                    {{ trans('general.time') }}
                </button>

                <div class="admin-hours__modal-edit-time-dropdown-menu">
                    <div data-custom-time="day"
                         class="admin-hours__modal-edit-time-dropdown-shift js-admin-hours__modal-edit-time-dropdown-shift">

                        <div class="admin-hours__modal-edit-time-dropdown-menu-item">
                            <div class="admin-hours__modal-edit-time-dropdown-menu-item--checkbox">
                                {{ Form::checkbox( 'select_all_custom_day',true, false,['class' => 'js-admin-hours__modal-edit-time-dropdown-menu-item--all-checkbox', 'id' => 'select_all_custom_day']) }}
                                <label for="select_all_custom_day"></label>
                            </div>
                            {{ Form::label('select_all_custom_day', trans('general.select_all') , ['class' => 'admin-hours__modal-edit-time-dropdown-menu-item--label']) }}
                        </div>

                        @for ($i=$dayStartMinutes; $i < $dayEndMinutes; $i+=$interval)
                            <div class="admin-hours__modal-edit-time-dropdown-menu-item">
                                <span class="admin-hours__modal-edit-time-dropdown-menu-item--checkbox">
                                    {{ Form::checkbox('custom_day_times[]',$i, false,['class' => 'js-admin-hours__modal-edit-time-dropdown-menu-item--checkbox','id' => 'custom_' . $i]) }}
                                    <label for="{{ 'custom_' . $i }}"></label>
                                </span>

                                {{ Form::label('custom_' . $i, \App\ScheduleSingleton::formatMinutes($i) , ['class' => 'admin-hours__modal-edit-time-dropdown-menu-item--label']) }}
                            </div>
                        @endfor
                    </div>

                    <div data-custom-time="night"
                         class="admin-hours__modal-edit-time-dropdown-shift js-admin-hours__modal-edit-time-dropdown-shift">

                        <div class="admin-hours__modal-edit-time-dropdown-menu-item">
                            <div class="admin-hours__modal-edit-time-dropdown-menu-item--checkbox">
                                {{ Form::checkbox( 'select_all_custom_night',true, false,['class' => 'js-admin-hours__modal-edit-time-dropdown-menu-item--all-checkbox', 'id' => 'select_all_custom_night']) }}
                                <label for="select_all_custom_night"></label>
                            </div>
                            {{ Form::label('select_all_custom_night', trans('general.select_all') , ['class' => 'admin-hours__modal-edit-time-dropdown-menu-item--label']) }}
                        </div>

                        @for ($i = $dayEndMinutes; $i < $nightEndMinutes; $i+=$interval)
                            <div class="admin-hours__modal-edit-time-dropdown-menu-item">
                                <span class="admin-hours__modal-edit-time-dropdown-menu-item--checkbox">
                                    {{ Form::checkbox('custom_night_times[]', $i, false,['class' => 'js-admin-hours__modal-edit-time-dropdown-menu-item--checkbox','id' => 'custom_' . $i]) }}
                                    <label for="{{ 'custom_' . $i }}"></label>
                                </span>

                                {{ Form::label('custom_' . $i, \App\ScheduleSingleton::formatMinutes($i) , ['class' => 'admin-hours__modal-edit-time-dropdown-menu-item--label']) }}
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        {!! Form::label('reason_for_change', trans('admin.reason_for_change'), ['class' => 'label']) !!}
        {!! Form::textarea('reason_for_change', NULL, ['class' => 'input js-admin-hours__modal-edit--reason', 'required']) !!}

        {{ Form::button(trans('general.submit'), ['type'=>'submit', 'class' => 'admin-hours__modal-edit--submit']) }}

        {{ Form::close() }}


    @endcomponent

    <!----- DELETE ITEM MODAL ------>
    @component('components.modal', ['class' => 'admin-hours__modal-delete js-admin-hours__modal-delete'])

        {{ Form::open(['method' => 'DELETE', 'action' => 'OffdaysController@index']) }}

        <div class="admin-hours__modal-delete--title">
            {{ trans('admin.delete_offday') }}
        </div>

        <div class="admin-hours__modal-delete--content">
            {{ trans('admin.delete_offday_confirmation_msg') }}
        </div>

        {{ Form::button(trans('general.confirm'), ['type'=>'submit','class' => 'admin-hours__modal-delete--submit']) }}
        {{ Form::close() }}

    @endcomponent
@stop

@section('script')
    <script>
        new Admin({
            language: '{{ $config::$language }}',
            dayStart: '{{ $config::$day_start }}',
            dayEnd: '{{ $config::$day_end }}',
            nightEnd: '{{ $config::$night_end }}',
            interval: '{{ $interval }}',
            offdays : '{!! json_encode($offdays->items()) !!}'
        }).reservationHours();
    </script>
@stop

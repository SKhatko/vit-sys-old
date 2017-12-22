@extends('admin.index')

@section('content')

    {!! Form::label('date_x', trans('general.date')) !!}
    {!! Form::text('date_x', date("d-m-Y", strtotime($offday->date)), ['class' => 'form-control', 'disabled']) !!}

    {!! Form::label('shift', trans('general.time_of_day')) !!}
    {!! Form::text('shift_x', $offday->shift, ['class' => 'form-control', 'disabled']) !!}


    {!! Form::open(['method' => 'patch', 'action' => ['OffdaysController@update', $offday->id]]) !!}


    {!! Form::label('status', trans('general.status')) !!} <br>
    <input type="radio" name="enabled" value="1" {{ $offday->enabled ? 'checked' : '' }}/> <span
            color="green">{{ trans('general.enabled') }}</span><br>
    <input type="radio" name="enabled" value="0" {{ $offday->enabled ? '' : 'checked' }}/> <span
            color="red">{{ trans('general.disabled') }}</span>

    <div class="times-holder" style="{{ $offday->enabled ? '' : 'display:none;' }}">

        <?php
        $dayStartMinutes = $schedule::timeToMinutes($config::$day_start);
        $dayEndMinutes = $schedule::timeToMinutes($config::$day_end);
        $nightEndMinutes = $schedule::timeToMinutes($config::$night_end);
        $interval = $schedule->interval;

        if ($dayStartMinutes % 15) {
            $dayStartMinutes = 15 - ($dayStartMinutes % 15) + $dayStartMinutes;
        }

        if ($dayEndMinutes % 15) {
            $dayEndMinutes = 15 - ($dayEndMinutes % 15) + $dayEndMinutes;
        }
        ?>

        @if ($offday->shift == 'day')
            @for ($i=$dayStartMinutes; $i < $dayEndMinutes; $i+=$interval)
                <span style="width:120px; display:inline-block;">
                                <input type="checkbox" name="times[]" value="{{ $i }}"
                                        {{ in_array($i, $offday->times_array) ? 'checked' : '' }} >
                    {{ \App\ScheduleSingleton::formatMinutes($i) }}
                            </span>
            @endfor
        @endif

        @if ($offday->shift == 'night')
            @for ($i = $dayEndMinutes; $i < $nightEndMinutes; $i+=$interval)
                <span style="width:120px; display:inline-block;">
                                <input type="checkbox" name="times[]" value="{{ $i }}"
                                        {{ in_array($i, $offday->times_array) ? 'checked' : '' }} >
                    {{ \App\ScheduleSingleton::formatMinutes($i) }}
                            </span>
            @endfor
        @endif


        {!! Form::label('reason_for_change', trans('admin.reason_for_change').' *') !!}
        {!! Form::textarea('reason_for_change', $offday->reason_for_change, ['class' => 'form-control', 'style' => 'max-height:100px;', 'required']) !!}


        {!! Form::button(trans('general.submit'), ['type'=>'submit','class' => 'btn btn-primary']) !!}

    </div>

    {!! Form::close() !!}
@stop

@section('script')
    <script>
        $(function () {
            $("input[name='enabled']").change(function () {

                if (this.value == 1) {
                    $(".times-holder").show();
                }
                else {
                    $(".times-holder").hide();
                }
            });
        });
    </script>
@stop
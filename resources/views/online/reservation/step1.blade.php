@extends('online.reservation.step_master')

@section('head')
    <link href="{{ asset('/css/r-picker.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/default.date.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/default.time.css') }}" rel="stylesheet">
@stop

@section('content')

    <div class="container">
        <div class="row">

            <div class="col-md-6 col-sm-6 col-xs-12 mboth">

                {!! Form::open(['method' => 'post', 'action' => 'Online\ReservationsController@postStep1']) !!}

                <div class="form-group">
                    {!! Form::label('persons_num', trans('general.persons').' *') !!}
                    {!! Form::select('persons_num', $personsArray, $selectedPersonsNum ? $selectedPersonsNum : 2 , ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('time_of_day', trans('general.time_of_day').' *') !!}
                    {!! Form::select('time_of_day', $timesOfDayArray, $selectedTimeOfDay ? $selectedTimeOfDay : null , ['class' => 'form-control']) !!}
                </div>

                <div class="form-group" id="date-group" style="display:none;">
                    {!! Form::label('date_x', trans('general.date').' *') !!}
                    <input type="text" name="date_x" class="datepicker form-control"/>
                    <input type="hidden" name="date"/>
                </div>

                <div class="loader" id="time-loader" style="padding:10px 0; display:none;">
                    <img src="{{ asset('/img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}..."/>
                </div>

                <div class="form-group" id="time-group" style="display:none;">
                    {!! Form::label('time_x', trans('general.time').' *') !!}
                    <input type="text" name="time_x" class="timepicker form-control" disabled/>
                    <input type="hidden" name="time"/>
                </div>

                <div class="form-group" id="btn-group">
                    <button type="submit" class="btn btn-primary disabled" id="step1-btn"
                            disabled>{{ trans('general.continue') }}</button>
                </div>

                {!! Form::close() !!}
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12 mboth mnone-xs">
                <div class="form-group">
                    <label class="xs-hide">&nbsp;</label>
                    <div class="content-box" style="margin-top:2px;">
                        <h3>{{ \App\Config::$restaurant_name }}</h3>

                        <p class="restaurant-address">
                            {{ \App\Config::$address }}, {{ \App\Config::$city }}
                        </p>

                        <p class="restaurant-message"
                           style="white-space: pre;">{!! \App\Config::$welcome_message !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')

    <script src="{{ asset('/js/legacy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/r-picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/picker.time.js') }}" type="text/javascript"></script>

    <script>
        //takes date in Y-m-d format, and parses it to javascript date object
        function parseDate(str) {
            var arr = str.split("-");
            return new Date(arr[0], arr[1] - 1, arr[2]);
        }

        function formatDate(year, month, day) {
            var result = year;
            if (month < 10) {
                result += "-0" + month;
            }
            else {
                result += "-" + month;
            }

            if (day < 10) {
                result += "-0" + day;
            }
            else {
                result += "-" + day;
            }

            return result;
        }

        function parseMinutes(mins) {
            var hours = Math.floor(mins / 60);
            var minutes = mins % 60;

            return [hours, minutes];
        }

        $(function () {

            var selectedDate = null;
            var selectedShift = null;

            var dayDisabledDates = '{!! json_encode($dayDisabledDates) !!}';
            var nightDisabledDates = '{!! json_encode($nightDisabledDates) !!}';

            var monthsFull = [
                '{{ trans('dates.january') }}',
                '{{ trans('dates.february') }}',
                '{{ trans('dates.march') }}',
                '{{ trans('dates.april') }}',
                '{{ trans('dates.may') }}',
                '{{ trans('dates.june') }}',
                '{{ trans('dates.july') }}',
                '{{ trans('dates.august') }}',
                '{{ trans('dates.september') }}',
                '{{ trans('dates.october') }}',
                '{{ trans('dates.november') }}',
                '{{ trans('dates.december') }}'
            ];

            var monthsShort = [
                '{{ trans('dates.jan') }}',
                '{{ trans('dates.feb') }}',
                '{{ trans('dates.mar') }}',
                '{{ trans('dates.apr') }}',
                '{{ trans('dates.may') }}',
                '{{ trans('dates.jun') }}',
                '{{ trans('dates.jul') }}',
                '{{ trans('dates.aug') }}',
                '{{ trans('dates.sep') }}',
                '{{ trans('dates.oct') }}',
                '{{ trans('dates.nov') }}',
                '{{ trans('dates.dec') }}'
            ];

            var weekdaysFull = [
                '{{ trans('dates.sunday') }}',
                '{{ trans('dates.monday') }}',
                '{{ trans('dates.tuesday') }}',
                '{{ trans('dates.wednesday') }}',
                '{{ trans('dates.thursday') }}',
                '{{ trans('dates.friday') }}',
                '{{ trans('dates.saturday') }}'
            ];

            var weekdaysShort = [
                '{{ trans('dates.sun') }}',
                '{{ trans('dates.mon') }}',
                '{{ trans('dates.tue') }}',
                '{{ trans('dates.wed') }}',
                '{{ trans('dates.thu') }}',
                '{{ trans('dates.fri') }}',
                '{{ trans('dates.sat') }}'
            ];

            var $input = $(".datepicker").pickadate({
                firstDay: 1,
                clear: '',
                today: '',
                close: '{{ trans('general.close') }}',
                monthsFull: monthsFull,
                monthsShort: monthsShort,
                weekdaysFull: weekdaysFull,
                weekdaysShort: weekdaysShort,
            });
            var picker = $input.pickadate('picker');

            picker.set('min', parseDate('{{ $minDate }}'));
            picker.set('max', parseDate('{{ $maxDate }}'));

            picker.on({
                'close': function (setThing) {
                    var selectedValue = picker.get('select');

                    if (selectedValue == null) {
                        resetGroupsOnDateChange();
                    }
                    else {
                        var year = selectedValue.year;
                        var month = selectedValue.month + 1;
                        var day = selectedValue.date;
                        var thisSelectedDate = formatDate(year, month, day);

                        if (thisSelectedDate == selectedDate) {
                            //do noth, it's same as already selected

                        }
                        else {
                            selectedDate = thisSelectedDate;
                            var selectedPersonsNum = $("select[name='persons_num'] :selected").val();
                            $("input[name='date']").val(thisSelectedDate);
                            resetGroupsOnDateChange();
                            $("#time-loader").show();
                            getAvailableTimes(selectedDate, selectedPersonsNum);
                        }
                    }
                }
            });

            function resetGroupsOnShiftChange() {
                $("#date-group").hide();
                $("input[name='date_x']").val('');
                $("input[name='date']").val('');
                $("input[name='date_x']").prop('disabled', true);
                resetGroupsOnDateChange();
            }

            function resetGroupsOnDateChange() {
                $("#time-group").hide();
                //clear input

                //$("#btn-group").hide();
                $("input[name='time_x']").val('');
                $("input[name='time']").val('');
                $("input[name='time_x']").prop('disabled', true);

                $("#btn-gorup .btn").addClass('disabled');
                $("#btn-group .btn").prop('disabled', true);
            }

            $("select[name='time_of_day']").change(function () {
                //alert(
                if ($(this).val() == 'day' || $(this).val() == 'night') {
                    selectedShift = $(this).val();
                    resetGroupsOnShiftChange();
                    getAvailableDates($(this).val());
                }
                else {
                    selectedShift = null;
                    resetGroupsOnShiftChange();
                }
            });

            $("select[name='persons_num']").change(function () {
//                $("#time_of_day").val('');
                resetGroupsOnDateChange();
            });

            function getAvailableDates(shift) {
                if (shift == 'day') {
                    picker.set('enable', [
                        @foreach ($nightDisabledDates as $date)
                            parseDate('{{ $date }}'),
                        @endforeach
                    ]);
                    picker.set('disable', [
                        @foreach ($dayDisabledDates as $date)
                            parseDate('{{ $date }}'),
                        @endforeach
                    ]);
                }
                else if (shift == 'night') {
                    picker.set('enable', [
                        @foreach ($dayDisabledDates as $date)
                            parseDate('{{ $date }}'),
                        @endforeach
                    ]);
                    picker.set('disable', [
                        @foreach ($nightDisabledDates as $date)
                            parseDate('{{ $date }}'),
                        @endforeach
                    ]);
                }

                $("#date-group").show();
                $("input[name='date_x']").prop('disabled', false);
            }

            //$(".timepicker").pickatime();
            function getAvailableTimes(date, personNum) {

                $.ajax({
                    method: "GET",
                    url: "{{ url('online-reservation/get-times') }}/" + date + "/" + selectedShift + "/" + personNum
                }).done(function (data) {

                    data = data['data'];

                    if (data.length == 0) {
                        alert('{!! trans('online.date_time_no_longer_available_msg') !!}');
                    }
                    else {
                        var enabledTimes = [true];
                        var minTime = null;
                        var maxTime = null;
                        for (var i = 0; i < data.length; i++) {

                            var mins = parseInt(data[i]);

                            var hourMinuteArr = parseMinutes(mins);


                            if (minTime == null || mins < minTime) {
                                minTime = mins;
                            }
                            if (maxTime == null || mins > maxTime) {
                                maxTime = mins;
                            }

                            enabledTimes.push(hourMinuteArr);
                        }

                        minTime = parseMinutes(minTime);
                        maxTime = parseMinutes(maxTime);

                        var timePicker = $(".timepicker").pickatime('picker');
                        if (timePicker) {
                            timePicker.set('min', minTime);
                            timePicker.set('max', maxTime);
                            timePicker.set('disable', true);
                            timePicker.set('disable', enabledTimes);
                        }
                        else {
                            $(".timepicker").pickatime({
                                clear: '',
                                format: 'HH:i',
                                interval: 15,
                                min: minTime,
                                max: maxTime,
                                disable: enabledTimes,
                                onClose: function () {
                                    var thisValue = this.get('select');
                                    if (thisValue != null) {
                                        $("input[name='time']").val(thisValue.time);
                                        $("#btn-group .btn").removeClass('disabled');
                                        $("#btn-group .btn").prop('disabled', false);
                                    }
                                    else {
                                        $("#btn-group .btn").addClass('disabled');
                                        $("#btn-group .btn").prop('disabled', true);
                                    }
                                }
                            });

                            @if ($lastAvailableStep > 1 && $selectedDate && $selectedMinutes)
                                    timePicker = $(".timepicker").pickatime('picker');
                            timePicker.set('select', parseMinutes({{ $selectedMinutes }}));
                            $("input[name='time']").val({{ $selectedMinutes }});
                            $("#btn-group .btn").removeClass('disabled');
                            $("#btn-group .btn").prop('disabled', false);
                            @endif
                        }

                        $("#time-group").show();
                        $("input[name='time_x']").prop('disabled', false);
                    }

                    $("#time-loader").hide();
                });
            }

            @if ($lastAvailableStep > 1 && $selectedTimeOfDay && $selectedDate && $selectedMinutes)

                    selectedShift = '{{ $selectedTimeOfDay }}';
            $("select[name='time_of_day']").val('{{ $selectedTimeOfDay }}');
            getAvailableDates(selectedShift);

            selectedDate = '{{ $selectedDate }}';
            picker.set('select', parseDate('{{ $selectedDate }}'));
            $("input[name='date']").val('{{ $selectedDate }}');
            $("#date-group").show();

            getAvailableTimes('{{ $selectedDate }}');
            $("#time-group").show();
            @endif

        });
    </script>
@stop
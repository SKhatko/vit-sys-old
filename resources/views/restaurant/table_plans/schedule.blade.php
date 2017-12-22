@extends('restaurant.index')

@section('header-print')
    @include('components.print_button')
@stop

@section('content')

    <div class="restaurant-schedule">
    @if(!count($sections))
        <!-- TODO style for warnings -->
            {{ trans('restaurant.add_sections_before_tables_warning_msg', ['sections_link' => '<a href="'.action('SectionsController@index').'">'.trans('restaurant.sections').'</a>'])  }}

        @elseif(!count($tablePlans))
            {{ trans('restaurant.add_table_plan_before_schedule_with_link', ['link_open' => '<a href="'.action('TablePlansController@create').'">', 'link_close' => '</a>'])  }}
        @else

            {{ Form::open(['method' => 'POST', 'action' => 'TablePlansController@updateTablePlanSchedule']) }}

            <div class="restaurant-schedule__default">

                <div class="restaurant-schedule__default-select">
                    {{ Form::label('default_table_plan_id', trans('restaurant.default_table_plan'), ['class' => 'restaurant__label']) }}
                    {{ Form::select('default_table_plan_id', $tablePlans, $defaultTablePlanId ?? $defaultTablePlanId, ['class' => 'select', 'required']) }}
                </div>

                {{ Form::button(trans('restaurant.update_table_plan_schedule'), ['type'=>'submit','class' => 'restaurant-schedule__default--submit']) }}


            </div>

            {{ Form::close() }}


            <div class="restaurant-schedule__daily">

                <div class="restaurant-schedule__daily-header">
                    <div title="Day of week" class="restaurant-schedule__daily-header--name">
                        Day of week
                    </div>

                    <div title="{{ trans('general.day') }}" class="restaurant-schedule__daily-header--day">
                        {{ trans('general.day') }}
                    </div>

                    <div title="{{ trans('general.night') }}" class="restaurant-schedule__daily-header--night">
                        {{ trans('general.night') }}
                    </div>

                    <div title="{{ trans('general.edit') }}" class="restaurant-schedule__daily-header--edit">
                        {{ trans('general.edit') }}
                    </div>
                </div>

                <div class="js-restaurant-schedule__daily-content">
                    @foreach ($dailyTablePlanSchedule as $dayName => $shift)
                        <div data-day="{{ $dayName }}" data-day-id="{{ $shift['day'] }}"
                             data-night-id="{{ $shift['night'] }}"
                             class="restaurant-schedule__daily-item js-restaurant-schedule__daily-item">

                            <div title="{{ trans('dates.' . $dayName ) }}" class="restaurant-schedule__daily-item--name js-restaurant-schedule__daily-item--name">
                                {{ trans('dates.' . $dayName ) }}
                            </div>

                            <div title="{{ $tablePlans[ (int)$shift['day'] ? $shift['day'] : $defaultTablePlanId] }}"
                                 class="restaurant-schedule__daily-item--day">
                                {{ $tablePlans[ (int)$shift['day'] ? $shift['day'] : $defaultTablePlanId] }}
                            </div>

                            <div title="{{ $tablePlans[ (int)$shift['night'] ? $shift['night'] : $defaultTablePlanId] }}"
                                 class="restaurant-schedule__daily-item--night">
                                {{ $tablePlans[ (int)$shift['night'] ? $shift['night'] : $defaultTablePlanId] }}
                            </div>

                            <div title="Edit" class="restaurant-schedule__daily-item--edit">
                                <svg class="restaurant-schedule__daily-item--edit-icon js-restaurant-schedule__daily-item--edit">
                                    <use xlink:href="#icon-edit"></use>
                                </svg>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>


            {{ Form::open(['method' => 'post', 'action' => 'TablePlansController@createTablePlanRecord']) }}

            <div class="restaurant-schedule__custom">

                <div class="restaurant-schedule__custom-date">
                    {{ Form::label('date', trans('restaurant.create_table_plan_on_date'), ['class' => 'restaurant__label']) }}
                    {{ Form::text('date', $date, ['class' => 'restaurant__input js-restaurant-schedule__custom-date', 'autocomplete' => 'off', 'required', 'placeholder' => trans('restaurant.select_date')]) }}
                </div>

                <div class="restaurant-schedule__custom-plan">
                    {{ Form::label('table_plan_id', trans('restaurant.select_plan'), ['class' => 'restaurant__label']) }}
                    {{ Form::select('table_plan_id', $tablePlans, null, ['class' => 'select', 'required']) }}
                </div>

                <div class="restaurant-schedule__custom-shift js-restaurant-schedule__custom-shift">
                    {{ Form::label('table_plan_id', trans('restaurant.select_shift'), ['class' => 'restaurant__label']) }}
                    {{ Form::select('shift', $shifts, null, ['class' => 'select', 'required']) }}
                </div>


                {{ Form::button(trans('general.create'), ['type'=>'submit','class' => 'restaurant-schedule__custom--submit js-restaurant-schedule__custom--submit']) }}

            </div>

            {{ Form::close() }}


            @if(count($tablePlanRecords))
                <div class="restaurant-schedule__records">
                    <div class="restaurant-schedule__records-header">
                        <div title="Day of week" class="restaurant-schedule__records-header--day">
                            Day of week
                        </div>

                        <div title="{{ trans('general.date') }}" class="restaurant-schedule__records-header--date">
                            {{ trans('general.date') }}
                        </div>

                        <div title="{{ trans('restaurant.table_plans') }}" class="restaurant-schedule__records-header--name">
                            {{ trans('restaurant.table_plans') }}
                        </div>

                        <div title="Time of day" class="restaurant-schedule__records-header--time">
                            Time of day
                        </div>

                        <div title="{{ trans('general.delete') }}" class="restaurant-schedule__records-header--delete">
                            {{ trans('general.delete') }}
                        </div>

                    </div>

                    @foreach ($tablePlanRecords as $tablePlanRecord)
                        <div data-record-id="{{ $tablePlanRecord->id }}"
                             class="restaurant-schedule__records-item js-restaurant-schedule__records-item">
                            <div title="{{ trans('general.'.strtolower(date('l', strtotime($tablePlanRecord->date)))) }}" class="restaurant-schedule__records-item--day">
                                {{ trans('general.'.strtolower(date('l', strtotime($tablePlanRecord->date)))) }}
                            </div>

                            <div title="{{ $tablePlanRecord->date }}" class="restaurant-schedule__records-item--date js-restaurant-schedule__records-item--date">
                                {{ $tablePlanRecord->date }}
                            </div>

                            <div title="{{ $tablePlans[$tablePlanRecord->table_plan_id] }}"
                                 class="restaurant-schedule__records-item--name">
                                {{ $tablePlans[$tablePlanRecord->table_plan_id] }}
                            </div>

                            <div title="{{ trans('general.'.$tablePlanRecord->shift) }}" data-time-of-day="{{ $tablePlanRecord->shift }}"
                                 class="restaurant-schedule__records-item--time js-restaurant-schedule__records-item--time">
                                {{ trans('general.'.$tablePlanRecord->shift) }}
                            </div>

                            <div title="Delete" class="restaurant-schedule__records-item--delete">
                                <svg class="restaurant-schedule__records-item--delete-icon js-restaurant-schedule__records-item--delete">
                                    <use xlink:href="#icon-cross"></use>
                                </svg>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        @endif

    </div>



@stop

@section('helpers')

    <!-- modal-rewrite -->
    @component('components.modal', ['class' => 'restaurant-schedule__modal-rewrite js-restaurant-schedule__modal-rewrite'])

        <div class="restaurant-schedule__modal-rewrite--title">
            Rewrite Custom Date
        </div>
        <div class="restaurant-schedule__modal-rewrite--content">
            You already have custom record for this date and shift. Are you sure you want to rewrite it?
        </div>

        {{ Form::button(trans('general.confirm'), ['class' => 'restaurant-schedule__modal-rewrite--submit js-restaurant-schedule__modal-rewrite--submit']) }}

    @endcomponent

    <!----- edit ITEM MODAL ------>
    @component('components.modal', ['class' => 'restaurant-schedule__modal-edit js-restaurant-schedule__modal-edit'])

        {{ Form::open(['method' => 'POST', 'action' => 'TablePlansController@updateTablePlanSchedule']) }}

        <div class="restaurant-schedule__modal-edit--title js-restaurant-schedule__modal-edit--title"></div>

        {{ Form::label('', trans('reception.day_shift'), ['class' => 'restaurant__label']) }}
        {{ Form::select('day', $tablePlans, 4, ['class' => 'select js-restaurant-schedule__modal-edit--day']) }}
        {{ Form::label('', trans('reception.night_shift'), ['class' => 'restaurant__label']) }}
        {{ Form::select('night', $tablePlans, 1, ['class' => 'select js-restaurant-schedule__modal-edit--night']) }}

        {{ Form::button(trans('general.update'), ['type'=>'submit','class' => 'restaurant-schedule__modal-edit--submit']) }}

        {{ Form::close() }}

    @endcomponent

    <!----- DELETE ITEM MODAL ------>
    @component('components.modal', ['class' => 'restaurant-schedule__modal-delete js-restaurant-schedule__modal-delete'])

        {{ Form::open(['method' => 'get', 'action' => 'TablePlansController@index']) }}

        <div class="restaurant-schedule__modal-delete--title">
            {{ trans('restaurant.delete_table_plan_record') }}
        </div>

        <div class="restaurant-schedule__modal-delete--content">
            {{ trans('restaurant.delete_table_plan_record_confirmation_msg') }}
        </div>

        {{ Form::button(trans('general.confirm'), ['type'=>'submit', 'class' => 'restaurant-schedule__modal-delete--submit']) }}

        {{ Form::close() }}

    @endcomponent

@stop

@section('script')
    <script>
        new Restaurant({
            language: '{{ \App\Config::$language }}',
        }).tablePlanSchedule();
    </script>
@stop

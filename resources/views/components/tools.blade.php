<div class="tools">

    <div class="tools__controls">
        <div class="tools__controls-datetime">

            <div class="tools__controls-date">

                {{ Form::label('day', trans('general.date'), ['class' => 'tools__controls-date--label'] ) }}

                {{ Form::button(trans('general.today'), ['class' => 'tools__controls-date--today js-tools__controls-date--today' ]) }}

                {{ Form::input('text', 'date', date('Y-m-d', strtotime($filterDate)), ['class' => 'tools__controls-date--input js-tools__controls-date--input', 'autocomplete' => 'off']) }}
            </div>

            <div class="tools__controls-time">

                {{ Form::label('', trans('general.time_of_day'), ['class' => 'tools__controls-time--lable']) }}

                <div class="tools__controls-time--buttons">

                    <button type="button"
                            class="tools__controls-time--button js-tools__controls-time--button {{ ($filterTimeOfDay == 'day') ? ' active' : '' }}"
                            data-value="day">
                        {{  array_first(explode( ":", \App\Config::$day_start)) }}
                        -{{  array_first(explode( ":", \App\Config::$day_end)) }}
                    </button>
                    <button type="button"
                            class="tools__controls-time--button js-tools__controls-time--button {{ ($filterTimeOfDay == 'night') ? ' active' : '' }}"
                            data-value="night">{{  array_first(explode( ":", \App\Config::$day_end)) }}
                        -{{  array_first(explode( ":", \App\Config::$night_end)) }}
                    </button>
                    <button type="button"
                            class="tools__controls-time--button js-tools__controls-time--button {{ ($filterTimeOfDay == 'all') ? ' active' : '' }}"
                            data-value="all">{{ trans('general.all') }}</button>

                </div>
            </div>

        </div>

        <div class="tools__controls-tables js-tools__controls-tables">
            <div class="tools__controls-tables--label">
                {{ trans('restaurant.tables_view') }}
            </div>
            <svg class="tools__controls-tables--icon">
                <use xlink:href="#icon-tables-view"></use>
            </svg>
        </div>

        <div class="tools__controls-filters">

            <div class="tools__controls-filters--button">
                <svg class="tools__controls-filters--button--icon">
                    <use xlink:href="#icon-arrow-down"></use>
                </svg>
            </div>

            {{ Form::text('search_input', null, ['class' => 'tools__controls-filters--input js-tools__controls-filters--input-search', 'placeholder' => trans('general.filter')]) }}
        </div>
    </div>

    <div class="tools__statuses">

        <div class="tools__statuses-radial js-tools__statuses-radial"
             data-reservations="">

            <svg class="radial-progress" width="115" height="115" viewport="0 0 100 100" version="1.1"
                 xmlns="http://www.w3.org/2000/svg">
                <circle r="50" cx="60" cy="60" fill="transparent" stroke-dasharray="315"
                        stroke-dashoffset="0"></circle>
                <circle class="bar" r="50" cx="60" cy="60" fill="transparent" stroke-dasharray="315"
                        stroke-dashoffset="0"></circle>
            </svg>

            <div class="tools__statuses-radial-count">
                <span class="tools__statuses-radial-count--persons js-tools__statuses-radial-count--persons"></span>
                <span class="tools__statuses-radial-count--all js-tools__statuses-radial-count--all"></span>
            </div>

            <div class="tools__statuses-radial--label">
                {{--                        {{ trans('reception.persons') }}--}}
                Persons
            </div>

        </div>

        <div class="tools__statuses-online">

            {{ Form::label('', trans('reception.online_status'), ['class' => 'tools__statuses-online--label']) }}

            <div class="tools__statuses-online-buttons">
                <div class="tools__statuses-online-button js-tools__statuses-online-button"
                     data-color="green">
                    <div class="tools__statuses-online-button--icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <div class="tools__statuses-online-button js-tools__statuses-online-button"
                     data-color="orange">
                    <div class="tools__statuses-online-button--icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <div class="tools__statuses-online-button js-tools__statuses-online-button"
                     data-color="red">
                    <div class="tools__statuses-online-button--icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>

            </div>

            <div class="tools__statuses-online-labels">
                <div class="tools__statuses-online-label">
                    {{ trans('reception.green_status') }}
                </div>
                <div class="tools__statuses-online-label">
                    {{ trans('reception.yellow_status') }}
                </div>
                <div class="tools__statuses-online-label">
                    {{ trans('reception.red_status') }}
                </div>
            </div>

        </div>

    </div>

    <div class="tools__analytics js-tools__analytics">

    </div>

</div>

@section('helpers')

    @parent

    <!----- DISABLE ONLINE / LIGHTS MODAL ------>
    @component('components.modal', ['class' => 'tools-lights__modal js-tools-lights__modal'])

        {{ Form::open(['method' => 'post', 'id' => 'lights-form', 'action' => 'ReservationsController@updateLights']) }}

        <div class="tools-lights__modal--title">
            {{ trans('reception.disable_online_reservations') }}
        </div>

        <input type="hidden" name="shift" id="lights-shift-input" value="{{ $filterTimeOfDay }}"/>
        <input type="hidden" name="date" id="lights-date-input" value="{{ $filterDate }}"/>

        {{ Form::label('online_closed', 'Disable reservations', ['class' => 'tools-lights__modal--label']) }}

        <div class="tools-lights__modal-columns">

            <div class="tools-lights__modal-select">

                <div class="tools-lights__madal-radio">
                    {{ Form::radio('online_closed', 1, false,['id' => 'online-close-input', 'class' => 'tools-lights__modal--radio']) }}
                    {{ Form::label('online-close-input', trans('reception.close_permanently'), ['class' => 'tools-lights__modal--label']) }}
                </div>

                <div class="tools-lights__madal-radio">
                    {{ Form::radio('online_closed', 0, true, ['id' => 'online-close-at-input', 'class' => 'tools-lights__modal--radio']) }}
                    {{ Form::label('online-close-at-input', trans('reception.automatic_close_at'), ['class' => 'tools-lights__modal--label']) }}
                </div>

            </div>

            <div class="tools-lights__modal-persons">

                {{ Form::number('online_stop_num', null, ['id' => 'online-stop-num-input', 'class' => 'tools-lights__modal--input']) }}

            </div>
        </div>




        {{ Form::label('user', trans('reception.action_by_name'), ['class' => 'tools-lights__modal--label']) }}
        {{ Form::text('user', NULL, ['class' => 'tools-lights__modal--input', 'id' => 'change-res-user', 'required', 'autocomplete' => 'off']) }}


        <h4>{{ trans('reception.previous_changes') }}</h4>

        <div id="stopped-day-log"></div>




        {{ Form::submit(trans('general.submit'), ['class' => 'tools-lights__modal--submit', 'id' => 'lights-modal-btn']) }}

        {{ Form::close() }}
    @endcomponent
@stop


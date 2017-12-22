<input type="hidden" name="client_id" value="{{ $reservation->client_id ? $reservation->client_id : '' }}">
<input type="hidden" name="company_id" value="{{ $reservation->company_id ? $reservation->company_id : '' }}">

<div class="reception-new-content">

    <div class="reception-new-block">

        <!-- TODO add class active on focus -->
        <div class="reception-new-info active">
            <div class="reception-new-info__header">
                {{ trans('reception.personal_information') }}
            </div>

            <div class="reception-new-info__content">

                {{ Form::label('gender', trans('general.gender'), ['class' => 'reception-new__label' ]) }}
                {{ Form::select('gender', $genders, $reservation->client ? $reservation->client->gender : NULL, ['class' => 'reception-new__input', 'required']) }}

                {{ Form::label('first_name', trans('general.first_name'), ['class' => 'reception-new__label' ]) }}
                {{ Form::text('first_name', $reservation->client ? $reservation->client->first_name : NULL, ['class' => 'reception-new__input autocomplete-client-first-name', 'autocomplete' => 'off']) }}

                {{ Form::label('last_name', trans('general.last_name'), ['class' => 'reception-new__label' ]) }}
                {{ Form::text('last_name', $reservation->client ? $reservation->client->last_name : $reservation->name, ['class' => 'reception-new__input autocomplete-client-last-name', 'required']) }}

                {{ Form::label('company_name', trans('general.company'), ['class' => 'reception-new__label' ]) }}
                {{ Form::text('company_name', $reservation->company_id ? $reservation->company->name : NULL, ['class' => 'reception-new__input autocomplete-company-name']) }}

                {{ Form::label('status_id', trans('crm.client_status'), ['class' => 'reception-new__label'] ) }}
                {{ Form::select('status_id', $clientStatuses, $reservation->client ? $reservation->client->status_id : NULL , ['class' => 'reception-new__input']) }}

                <div class="reception-new__columns">
                    <div class="reception-new__column">
                        {{ Form::label('phone', trans('general.phone_num'), ['class' => 'reception-new__label']) }}
                        {{ Form::text('phone', $reservation->client ? $reservation->client->phone : NULL, ['class' => 'reception-new__input autocomplete-client-phone']) }}
                    </div>

                    <div class="reception-new__column">
                        {{ Form::label('mobile', trans('general.mobile'), ['class' => 'reception-new__label']) }}
                        {{ Form::text('mobile', $reservation->client ? $reservation->client->mobile : NULL, ['class' => 'reception-new__input autocomplete-client-mobile']) }}
                    </div>
                </div>

                {{ Form::label('email', trans('auth.email'), ['class' => 'reception-new__label']) }}
                {{ Form::email('email', $reservation->client ? $reservation->client->email : NULL, ['class' => 'reception-new__input']) }}

            </div>
        </div>

    </div>

    <div class="reception-new-block">
        <div class="reception-new-info">
            <div class="reception-new-info__header">
                {{ trans('reception.order_information') }}
            </div>

            <div class="reception-new-info__content">


                <div class="reception-new__columns">
                    <div class="reception-new__column">
                        <!-- TODO datepicker -->
                        {{ Form::label('date', trans('general.date'), ['class' => 'reception-new__label']) }}
                        {{ Form::text('date', ($reservation->date) ? date("Y-m-d", strtotime($reservation->date)) : $date, ['class' => 'reception-new__input js-reception-new__input--date', 'required', 'autocomplete' => 'off']) }}
                    </div>

                    <div class="reception-new__column">
                        {{ Form::label('time', trans('general.time'), ['class' => 'reception-new__label']) }}
                        {{ Form::input('text', 'time', ($reservation->time) ? date("H", strtotime($reservation->time)) : $time, ['class' => 'reception-new__input js-reception-new__input--time', 'required']) }}
                    </div>
                </div>

                <div class="reception-new__columns">
                    <div class="reception-new__column">
                        {{ Form::label('persons_num', trans('general.persons_num'), ['class' => 'reception-new__label']) }}
                        {{ Form::input('number', 'persons_num', $reservation->persons_num, ['class' => 'reception-new__input', 'required']) }}
                    </div>

                    <div class="reception-new__column">
                        {{ Form::label('table_id', trans('restaurant.table'), ['class' => 'reception-new__label']) }}
                        {{ Form::text('table_id', ($reservation->table_id != 0) ? $reservation->table_id : '', ['class' => 'reception-new__input']) }}

                    </div>
                </div>

                {{ Form::label('event_id', trans('reception.event_type'), ['class' => 'reception-new__label']) }}
                {{ Form::select('event_type_id', $eventTypes, $reservation->client ? $reservation->event_type_id : NULL , ['class' => 'reception-new__input']) }}

                {{ Form::label('description', trans('reception.note'), ['class' => 'reception-new__label']) }}
                {{ Form::textarea('description', $reservation->description, ['class' => 'reception-new__input--textarea']) }}

                {{ Form::label('sticky_note', trans('crm.sticky_note'), ['class' => 'reception-new__label']) }}
                {{ Form::textarea('sticky_note', $reservation->client_id ? $reservation->client->sticky_note : NULL, ['class' => 'reception-new__input--textarea']) }}

                {{ Form::label('status_id', trans('general.status'), ['class' => 'reception-new__label']) }}
                {{ Form::select('status_id', $statuses, null, ['class' => 'reception-new__input']) }}

            </div>
        </div>
    </div>

    <div class="reception-new-block">
        <div class="reception-new-info">
            <div class="reception-new-info__header">
                {{ trans('reception.offer_status') }}
            </div>

            <div class="reception-new-info__content">


                {{ Form::label('offer_status_id', trans('reception.offer_status'), ['class' => 'reception-new__label']) }}
                {{ Form::select('offer_status_id', $offerStatuses, $reservation->client ? $reservation->offer_status_id : NULL , ['class' => 'reception-new__input']) }}

                {{ Form::label('offer_file_upload', trans('reception.offer_file'), ['class' => 'reception-new__label']) }}

                <div class="reception-new__file">
                    <div class="reception-new__file-upload">
                        <svg class="reception-new__file-upload--icon">
                            <use xlink:href="#icon-attachment"></use>
                        </svg>
                        {{ trans('reception.attach_file') }}

                        {{ Form::file('offer_file_upload', ['class' => 'reception-new__file-upload--default']) }}
                    </div>

                    <div class="reception-new__file-remove">
                        <svg class="reception-new__file-remove--icon">
                            <use xlink:href="#icon-cross"></use>
                        </svg>
                    </div>
                </div>

                <!-- TODO design when file uploaded -->
                @if ($reservation->offer_file)
                    <a href="{{ action('ReservationsController@serveOffer', [$reservation->id]) }}" target="_blank"
                       title="{{ $reservation->offer_file }}" id="offer-link">
                        {{ substr($reservation->offer_file, 0, 25) }}...
                    </a>
                    <a href="javascript:;" style="color:red;"
                       id="delete-offer-link">{{ trans('general.delete') }}</a>
                @endif


            </div>
        </div>

        <div class="reception-new-info">
            <div class="reception-new-info__header">
                {{ trans('kitchen.preorder_configurations') }}
            </div>

            <div class="reception-new-info__content">

                <div class="reception-new-config {{ \App\Config::$has_kitchen ? 'active' : '' }}">

                    {{ Form::label('preorders_configuration', trans('kitchen.preorder_configurations'), ['class' => 'reception-new__label']) }}
                    {{ Form::select('configuration_type', $preorderConfigurations, $reservation->reservation_configuration, ['class' => 'reception-new__input js-reception-new-config--type']) }}

                    <div class="reception-new__columns">
                        <div class="reception-new__column">
                            <div class="reception-new__checkbox">
                                {{ Form::checkbox('default_display_prices', $reservation->reservation_configuration ? $reservation->reservation_configuration->display_prices : \App\PreordersConfig::getInstance()->display_prices, \App\PreordersConfig::getInstance()->display_prices, ['id' => 'default_display_prices', 'class' => 'js-reception-new-config__checkbox']) }}
                                <label for="default_display_prices"></label>
                            </div>
                            {{ Form::label('default_display_prices',  trans('menu.display_prices'), ['class' => 'reception-new__checkbox--label']) }}
                        </div>

                        <div class="reception-new__column">
                            <div class="reception-new__checkbox">
                                {{ Form::checkbox('default_display_images', $reservation->reservation_configuration ? $reservation->reservation_configuration->display_images : \App\PreordersConfig::getInstance()->display_images, \App\PreordersConfig::getInstance()->display_images, ['id' => 'default_display_images', 'class' => 'js-reception-new-config__checkbox']) }}
                                <label for="default_display_images"></label>
                            </div>
                            {{ Form::label('default_display_images',  trans('menu.display_images'), ['class' => 'reception-new__checkbox--label']) }}
                        </div>
                    </div>

                    <div class="reception-new__columns">
                        {{ Form::number('default_hours_limit', $reservation->reservation_configuration ? $reservation->reservation_configuration->hours_limit : \App\PreordersConfig::getInstance()->hours_limit, ['class' => 'reception-new__input--number js-reception-new-config__hours', 'min' => 0 ]) }}
                        {{ Form::label('default_hours_limit', trans('reception.hours_limit_with_input'), ['class' => 'reception-new__label']) }}
                    </div>

                    {{ Form::label('custom_menu_id', trans('restaurant.preorder_menu'), ['class' => 'reception-new__label']) }}
                    {{ Form::select('custom_menu_id', $customMenus, $reservation->reservation_configuration ? $reservation->reservation_configuration->custom_menu_id : null, ['class' => 'reception-new__input js-reception-new__menu ', 'disabled']) }}

                    @if ($reservation->id)
                    <!-- TODO design -->
                        <hr>

                        <div class="form-group">
                            {{ trans('reception.online_url') }}: <a
                                    href="{{ action('Online\PreordersController@index', [$reservation->identifier, $reservation->url_token]) }}"
                                    target="_blank">{{ trans('general.open') }}</a><br>

                            {{ trans('general.persons') }}:
                            <strong>{!! $preordersCount ? '<span class="text-success">'.$preordersCount.'</span>' : '<span class="text-danger">'.$preordersCount.'</span>' !!}</strong>
                            @if ($preordersCount)
                                (<a href="{{ action('PreordersController@show', [$reservation->id]) }}"
                                    target="_blank">{{ trans('general.view') }}</a>)
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<div class="reception-new-footer">

    <div class="reception-new-creator">
    {{ Form::label('user', trans('reception.action_by_name'), ['class' => 'reception-new__label']) }}
    <!-- TODO typed logged user name -->
        {{ Form::text('user', NULL, ['class' => 'reception-new__input', 'required']) }}
    </div>

    <div class="reception-new-submit">
        <input type="hidden" name="delete_offer" value="0"/>
        {!! Form::submit($submitButtonText, ['class' => 'reception-new-submit__button']) !!}
    </div>

</div>

@section('script')
    <script>
        new Reception({
            language: '{{ \App\Config::$language }}',
            dayStart: '{!! \App\Config::$day_start !!}',
            nightEnd: '{!! \App\Config::$night_end !!}',
        }).newReservation();
    </script>
@stop



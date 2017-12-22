@extends('restaurant.index')

@section('header-print')
    @include('components.print_button')
@stop

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@stop

@section('content')

    <div class="restaurant-plan">

        @if (!count($sections))

            @include('partials.error', [
        'message' => trans('restaurant.add_sections_before_tables_error_msg'),
        'action' => action('SectionsController@index'),
        'actionName' => trans('restaurant.sections')
         ])

        @else

            {{ Form::open(['action' => ['TablePlansController@update', $tablePlan->id], 'method' => 'PATCH']) }}

            @include('components.back', ['text' => 'Back'])

            <div class="restaurant-plan__header">
                <div class="restaurant-plan__header-plans">
                    <div class="restaurant-plan__header-plans-select">
                        {{ Form::hidden('table_plan_objects', count($tablePlanObjects) ? json_encode($tablePlanObjects) : null, ['class' => 'js-table-plan-objects']) }}
                        {{ Form::label('table_plan_select', trans('restaurant.select_plan'), ['class' => 'restaurant__label']) }}
                        {{ Form::select('table_plan_select', $tablePlans, $tablePlan->id, ['class' => 'restaurant-plan__header-plans-select-plans']) }}
                    </div>

                    <a href="{{ action('TablePlansController@index') }}" class="restaurant-plan__header-plans-add">
                        <svg class="restaurant-plan__header-plans-add--icon">
                            <use xlink:href="#icon-cross"></use>
                        </svg>
                    </a>
                </div>

                <div class="restaurant-plan__header-sections">
                    <div class="restaurant-plan__header-sections-select">
                        {{ Form::label('section_select', trans('restaurant.select_section'), ['class' => 'restaurant__label']) }}

                        <ul class="restaurant-plan__header-sections-select-carousel owl-carousel">
                            @foreach(array_slice($sections, 0, 3) as $sectionId => $sectionName)
                                <li title="{{ $sectionName }}"
                                    class="restaurant-plan__header-sections-select-list--item {{ $loop->first ? 'active' : '' }}">
                                    <a class="restaurant-plan__header-sections-select--link" data-toggle="tab"
                                       href="#section-{{ $sectionId }}">
                                        {{ $sectionName }}
                                    </a>
                                </li>

                            @endforeach

                        </ul>
                    </div>
                    <a class="restaurant-plan__header-sections-show js-restaurant-plan__header-sections-show">
                        <svg class="restaurant-plan__header-sections-show--icon">
                            <use xlink:href="#arrow-down"></use>
                        </svg>
                    </a>
                    <a href="{{ action('SectionsController@index') }}" class="restaurant-plan__header-sections-add">
                        <svg class="restaurant-plan__header-sections-add--icon">
                            <use xlink:href="#icon-cross"></use>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="restaurant-plan__tools">
                <div class="restaurant-plan__tools-buttons">

                    <div class="restaurant-plan__tools-button js-restaurant-plan__tools-button active"
                         data-object-type="table" draggable="true" title="{{ trans('restaurant.table') }}">
                        <svg class="restaurant-plan__tools-button--icon">
                            <use xlink:href="#icon-table"></use>
                        </svg>
                    </div>

                    <div class="restaurant-plan__tools-button js-restaurant-plan__tools-button active"
                         data-object-type="wall" draggable="true" title="{{ trans('restaurant.wall') }}">
                        <svg class="restaurant-plan__tools-button--icon">
                            <use xlink:href="#icon-wall"></use>
                        </svg>
                    </div>

                    <div class="restaurant-plan__tools-button js-restaurant-plan__tools-button active"
                         data-object-type="plant" draggable="true" title="{{ trans('restaurant.plant') }}">
                        <svg class="restaurant-plan__tools-button--icon">
                            <use xlink:href="#icon-plant"></use>
                        </svg>
                    </div>

                    <div class="restaurant-plan__tools-button js-restaurant-plan__tools-button active"
                         data-object-type="pillar" draggable="true" title="{{ trans('restaurant.pillar') }}">
                        <svg class="restaurant-plan__tools-button--icon">
                            <use xlink:href="#icon-pillar"></use>
                        </svg>
                    </div>
                </div>
                <div class="restaurant-plan__tools-properties-wrapper">
                    <div class="restaurant-plan__tools-properties js-restaurant-plan__tools-properties">

                        <div class="restaurant-plan__tools-properties-input">
                            {{ Form::label('label', trans('restaurant.object_label'), ['class' => 'restaurant__label']) }}
                            {{ Form::text('label', null, ['class' => 'restaurant__input']) }}
                        </div>
                        <div class="restaurant-plan__tools-properties-input">
                            {{ Form::label('width', trans('restaurant.object_width'), ['class' => 'restaurant__label']) }}
                            {{ Form::number('width', null, ['class' => 'restaurant__input', 'min' => 0, 'max' => 100 ]) }}
                        </div>
                        <div class="restaurant-plan__tools-properties-input">
                            {{ Form::label('height', trans('restaurant.object_height'), ['class' => 'restaurant__label']) }}
                            {{ Form::number('height', null, ['class' => 'restaurant__input', 'min' => 0, 'max' => 100 ]) }}
                        </div>
                        <div class="restaurant-plan__tools-properties-input">
                            {{ Form::label('border-radius', trans('restaurant.object_border_radius'), ['class' => 'restaurant__label']) }}
                            {{ Form::number('border-radius', null, ['class' => 'restaurant__input', 'min' => 0, 'max' => 50, 'step' => 5]) }}
                        </div>

                    </div>

                    <div class="restaurant-plan__tools-properties-save js-restaurant-plan__tools-properties-save">
                        {{ Form::button(trans('restaurant.save_changes'), ['type'=>'submit','class' => 'restaurant-plan__tools-properties-save--submit js-restaurant-plan__tools-save--submit']) }}
                    </div>
                </div>

            </div>

            <div class="tab-content restaurant-plan__content">
                @foreach($sections as $sectionId => $sectionName)
                    <div id="section-{{ $sectionId }}" data-section-id="{{ $sectionId }}"
                         class="section-content tab-pane fade">
                        <div class="section-map"></div>
                    </div>
                @endforeach
            </div>

            {{ Form::close() }}

        @endif
    </div>

@stop

@section('helpers')

    <!----- Add table modal------>
    @component('components.modal', ['modalId' => 'add-table-modal', 'class' => 'restaurant-plan__modal-create js-restaurant-plan__modal-create'])

        <div class="restaurant-plan__modal-create--title">
            {{ trans('restaurant.add_table_object') }}
        </div>

        {{ Form::label('object_num', trans('restaurant.table_number'), ['class' => 'restaurant__label']) }}

        {{ Form::text('object_num', 1, ['class' => 'restaurant__input object-num-input', 'autofocus' => 'autofocus', 'data-trigger'=>'focus', 'data-toggle' => 'tooltip', 'data-placement' =>'left', 'title' => trans('restaurant.table_number_invalid_msg')]) }}


        {{ Form::label('persons_num', trans('restaurant.table_persons_number'), ['class' => 'restaurant__label']) }}
        {{ Form::number('persons_num', 2, ['class' => 'restaurant__input persons-num-input', 'min' => 1, 'max' => 30, 'data-trigger'=>'focus', 'data-toggle' => 'tooltip', 'data-placement' =>'left', 'title' => trans('restaurant.persons_number_required_msg')]) }}

        {{ Form::button(trans('general.submit'), ['type'=>'submit','class' => 'restaurant-plan__modal-create--submit create-table-button']) }}

    @endcomponent


    <!----- Edit table modal------>
    @component('components.modal', ['class' => 'restaurant-plan__modal-edit js-restaurant-plan__modal-edit'])

        <div class="restaurant-plan__modal-edit--title">
            {{ trans('restaurant.edit_table_object') }}
        </div>

        {{ Form::label('object_num', trans('restaurant.table_number'), ['class' => 'col-xs-8']) }}
        {{ Form::text('object_num', 1, ['class' => 'restaurant__input object-num-input', 'autofocus', 'data-toggle' => 'tooltip', 'title' => trans('restaurant.table_number_invalid_msg')]) }}

        {{ Form::label('persons_num', trans('restaurant.table_persons_number'), ['class' => 'col-xs-8']) }}
        {{ Form::number('persons_num', 2, ['class' => 'restaurant__input persons-num-input', 'min' => 1, 'max' => 30, 'data-toggle' => 'tooltip', 'title' => trans('restaurant.persons_number_required_msg')]) }}

        {{ Form::button(trans('general.update'), ['class' => 'restaurant-plan__modal-edit--submit update-table-button']) }}

    @endcomponent

@stop

@section('script')

    <!-- TODO remove links -->
    {{--<script>let _tooltip = jQuery.fn.tooltip;</script>--}}
    {{--    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>--}}
    {{--<script>$.fn.tooltip = _tooltip;</script>--}}
    <script>

        new Restaurant({
            tablePlanObjects: JSON.parse('{!! $tablePlanObjects !!}'),
            tablePlanId: '{!! $tablePlan->id !!}',
            deleteObjectMsg: '{!! trans('restaurant.delete_object_confirmation_msg') !!}',
        }).tablePlan();

    </script>

@stop

@extends('restaurant.index')

@section('header-print')
    @include('components.print_button')
@stop

@section('content')

    <div class="restaurant-plans">
        @if(count($sections))

            {{ Form::open(['action' => ['TablePlansController@store'], 'method' => 'POST']) }}

            <div class="restaurant-plans__create">
                <div class="restaurant-plans__create--name">
                    {{ Form::label('table_plan_create', trans('restaurant.table_plan_name'), ['class' => 'label']) }}
                    {{ Form::text('table_plan_create', NULL, ['class' => 'input', 'required', 'autofocus' => 'autofocus']) }}
                </div>

                <div class="restaurant-plans__create--select">
                    {{ Form::label('table_plan_create_from', trans('restaurant.copy_table_plan_from'), ['class' => 'label']) }}
                    {{ Form::select('table_plan_create_from', $tablePlans->pluck('name', 'id'), null, ['class' => 'select', 'placeholder' => trans('restaurant.select_plan')]) }}
                </div>

                {{ Form::button(trans('general.create'), ['type'=>'submit', 'class' => 'restaurant-plans__create--submit']) }}

            </div>

            {{ Form::close() }}

            @if(count($tablePlans))
                <div class="restaurant-plans__list">
                    <div class="restaurant-plans__list-header">
                        <div title="Table plan name" class="restaurant-plans__list-header--name">
                            Table plan name
                        </div>

                        <div title="{{ trans('general.edit') }}" class="restaurant-plans__list-header--edit">
                            {{ trans('general.edit') }}
                        </div>

                        <div title="{{ trans('general.delete') }}" class="restaurant-plans__list-header--delete">
                            {{ trans('general.delete') }}
                        </div>

                    </div>

                    <div class="js-restaurant-plans__list-content">
                        @foreach ($tablePlans as $tablePlan)
                            <div data-plan="{{ $tablePlan->id }}" id="{{ $tablePlan->id }}"
                                 class="restaurant-plans__list-item js-restaurant-plans__list-item">

                                <svg class="restaurant-plans__list-item-icon js-restaurant-plans__list-item-icon">
                                    <use xlink:href="#icon-drag"></use>
                                </svg>

                                <a href="{{ action('TablePlansController@show', [$tablePlan->id]) }}"
                                   title="{{ trans('general.edit') }}"
                                   class="restaurant-plans__list-item--name js-restaurant-plans__list-item--name">
                                    {{ $tablePlan->name }}
                                </a>

                                <div class="restaurant-plans__list-item--edit">
                                    <svg class="restaurant-plans__list-item--edit-icon js-restaurant-plans__list-item--edit">
                                        <use xlink:href="#icon-edit"></use>
                                    </svg>
                                </div>

                                <div class="restaurant-plans__list-item--delete">
                                    <svg class="restaurant-plans__list-item--delete-icon js-restaurant-plans__list-item--delete">
                                        <use xlink:href="#icon-cross"></use>
                                    </svg>
                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

            @endif

        @else

            {!! trans('restaurant.add_sections_before_tables_warning_msg', ['sections_link' => '<a href="'.action('SectionsController@index').'">'.trans('restaurant.sections').'</a>']) !!}

        @endif
    </div>


@stop

@section('helpers')

    <!----- edit table plan name MODAL ------>
    @component('components.modal', ['class' => 'restaurant-plans__modal-edit js-restaurant-plans__modal-edit'])

        {{ Form::open(['method' => 'PATCH', 'action' => 'TablePlansController@index'])  }}

        <div class="restaurant-plans__modal-edit--title">
            {{ trans('restaurant.rename_table_plan') }}
        </div>

        {{ Form::label('table_plan_create', trans('restaurant.table_plan_name'), ['class' => 'label']) }}
        {{ Form::text('table_plan_create', NULL, ['class' => 'restaurant__input js-restaurant-plans__modal--name', 'required', 'autofocus' => 'autofocus']) }}

        {{ Form::button(trans('general.update'), ['type'=>'submit','class' => 'restaurant-plans__modal-edit--submit']) }}

        {{ Form::close() }}

    @endcomponent

    <!----- DELETE table plan MODAL ------>
    @component('components.modal', ['class' => 'restaurant-plans__modal-delete js-restaurant-plans__modal-delete'])

        {{ Form::open(['method' => 'DELETE', 'action' => 'TablePlansController@index']) }}

        <div class="restaurant-plans__modal-delete--title">
            {{ trans('restaurant.delete_table_plan') }}
        </div>

        <div class="restaurant-plans__modal-delete--content">
            {{ trans('restaurant.delete_table_plan_confirmation_msg') }}
        </div>

        {{ Form::button(trans('general.confirm'), ['type'=>'submit', 'class' => 'restaurant-plans__modal-delete--submit']) }}

        {{ Form::close() }}

    @endcomponent

@stop

@section('script')
    <script>
        new Restaurant().tablePlans();
    </script>
@stop
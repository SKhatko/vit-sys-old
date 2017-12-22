@extends('reception.master')

@section('header-print')
    @include('components.print_button')
@stop

@section('nav')

    <li class="sidebar-nav__menu-item {{ $pageName == 'reception' ? 'current active' : '' }}" data-page="reception">
        <a href="{{ action('ReservationsController@index') }}" class="sidebar-nav__menu-item--link">
            {{ trans('reception.reservations') }}
        </a>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>

    <li class="sidebar-nav__menu-item {{ $pageName == 'reception-create' ? 'current active' : '' }}"
        data-page="reception-create">
        <a href="{{ action('ReservationsController@create') }}/{{ $filterDate or '' }}"
           class="sidebar-nav__menu-item--link">
            {{ trans('reception.new_reservation') }}
        </a>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>

    <li class="sidebar-nav__menu-item">
        <a href="javascript:" class="sidebar-nav__menu-item--link js-reception-new__quick--button">
            {{ trans('reception.quick_reservation') }}
        </a>

        <span class="sidebar-nav__menu-item--tail"></span>
    </li>

    <li class="sidebar-nav__menu-item">
        <a href="javascript:" class="sidebar-nav__menu-item--link js-reception-new__walk-in--button">
            {{ trans('reception.walk_in') }}
        </a>

        <span class="sidebar-nav__menu-item--tail"></span>
    </li>

@stop

@section('helpers')

    @component('components.modal', ['class' => 'reception-walk-in__modal js-reception-walk-in__modal'])

        {{ Form::open(['method' => 'POST','action' => 'ReservationsController@postWalkin']) }}

        <div class="reception-walk-in__modal--title">
            {{ trans('reception.walk_in') }}
        </div>

        <input type="hidden" name="client_id" value="">

        {{ Form::label('name', trans('general.name'), ['class' => 'reception-walk-in__modal--label']) }}
        {{ Form::text('name', NULL, ['class' => 'reception-walk-in__modal--input']) }}

        {{ Form::label('persons_num', trans('general.persons'), ['class' => 'reception-walk-in__modal--label']) }}
        {{ Form::input('number', 'persons_num', NULL, ['class' => 'reception-walk-in__modal--input', 'required', 'min' => 0 ])  }}


        {{ Form::label('table_id', trans('restaurant.table'), ['class' => 'reception-walk-in__modal--label']) }}
        {{ Form::input('number', 'table_id', NULL, ['class' => 'reception-walk-in__modal--input', 'min' => 0 ]) }}


        {{ Form::label('user', trans('reception.action_by_name'), ['class' => 'reception-walk-in__modal--label'])  }}
        {{ Form::text('user', NULL, ['class' => 'reception-walk-in__modal--input', 'required']) }}


        {{ Form::submit(trans('general.create'), ['class' => 'reception-walk-in__modal--submit']) }}

        {{ Form::close() }}

    @endcomponent

    @component('components.modal', ['class' => 'reception-quick__modal js-reception-quick__modal'])

        {{ Form::open(['method' => 'POST', 'action' => 'ReservationsController@store']) }}

        {{ Form::hidden('client_id', null) }}

        <div class="reception-quick__modal--title">
            {{ trans('reception.quick_reservation') }}
        </div>

        {{ Form::label('first_name', trans('general.first_name'), ['class' => 'reception-quick__modal--label']) }}
        {{ Form::text('first_name', NULL, ['class' => 'reception-quick__modal--input autocomplete-client-first-name']) }}

        {{ Form::label('last_name', trans('general.last_name'), ['class' => 'reception-quick__modal--label']) }}
        {{ Form::text('last_name', NULL, ['class' => 'reception-quick__modal--input autocomplete-client-last-name', 'required']) }}

        <div class="reception-new__columns">
            <div class="reception-new__column">
                {{ Form::label('date', trans('general.date'), ['class' => 'reception-quick__modal--label']) }}
                {{ Form::text('date', null, ['class' => 'reception-quick__modal--input js-reception-quick__modal--date', 'required', 'autocomplete' => 'off']) }}
            </div>

            <div class="reception-new__column">
                {{ Form::label('time', trans('general.time'), ['class' => 'reception-quick__modal--label']) }}
                {{ Form::text('time', null, ['class' => 'reception-quick__modal--input js-reception-quick__modal--time', 'required']) }}
            </div>
        </div>

        {{ Form::label('persons_num', trans('general.persons'), ['class' => 'reception-quick__modal--label']) }}
        {{ Form::input('number', 'persons_num', NULL, ['class' => 'reception-quick__modal--input', 'required', 'min' => 0 ]) }}

        {{ Form::label('phone', trans('general.phone_num'), ['class' => 'reception-quick__modal--label']) }}
        {{ Form::text('phone', NULL, ['class' => 'reception-quick__modal--input autocomplete-client-phone' ]) }}

        {{ Form::label('mobile', trans('general.mobile'), ['class' => 'reception-quick__modal--label']) }}
        {{ Form::text('mobile', NULL, ['class' => 'reception-quick__modal--input autocomplete-client-mobile']) }}

        {{ Form::label('email', trans('auth.email'), ['class' => 'reception-quick__modal--label']) }}
        {{ Form::email('email', NULL, ['class' => 'reception-quick__modal--input']) }}

        {{ Form::label('description', trans('reception.note'), ['class' => 'reception-quick__modal--label']) }}
        {{ Form::textarea('description', null, ['class' => 'reception-quick__modal--textarea']) }}

        {{ Form::label('user', trans('reception.action_by_name'), ['class' => 'reception-quick__modal--label']) }}
        {{ Form::text('user', NULL, ['class' => 'reception-quick__modal--input', 'required']) }}

        {{ Form::submit(trans('general.create'), ['class' => 'reception-quick__modal--submit']) }}

        {{ Form::close() }}

    @endcomponent

@stop
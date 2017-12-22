@extends('restaurant.index')

@section('header-print')
    @include('components.print_button')
@stop

@section('content')

    <div class="restaurant-menu js-restaurant-menu">

        @include('components.back')

        {!! Form::model($customMenu = new \App\CustomMenu, ['action' => 'CustomMenusController@store', 'method' => 'POST']) !!}
        @include('restaurant.custom_menus._form', ['submitButtonText' => trans('restaurant.create_custom_menu')])
        {!! Form::close() !!}
    </div>

@stop

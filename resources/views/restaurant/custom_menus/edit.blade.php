@extends('restaurant.index')

@section('header-print')
    @include('components.print_button')
@stop

@section('content')

    <div class="restaurant-menu js-restaurant-menu">

        @include('components.back')

        {!! Form::model($customMenu, ['action' => ['CustomMenusController@update', $customMenu->id], 'method' => 'PATCH']) !!}
        @include('restaurant.custom_menus._form', ['submitButtonText' => trans('general.submit')])
        {!! Form::close() !!}
    </div>

@stop

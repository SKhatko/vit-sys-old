@extends('restaurant.index')

@section('content')
    <div class="restaurant-group js-restaurant-group">
        @include('components.back')

        {!! Form::model($group = new \App\MenuGroup, ['action' => 'MenuGroupsController@store', 'method' => 'POST', 'files' => true, 'id' => 'group-form']) !!}
        @include('restaurant.menu_groups._form', ['submitButtonText' => trans('restaurant.create_menu')])
        {!! Form::close() !!}
    </div>
@stop

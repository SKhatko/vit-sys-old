@extends('restaurant.index')

@section('content')
    <div class="restaurant-group js-restaurant-group">
        @include('components.back')

        {!! Form::model($group, ['action' => ['MenuGroupsController@update', $group->id], 'method' => 'PATCH', 'files' => true, 'id' => 'group-form']) !!}
        @include('restaurant.menu_groups._form', ['submitButtonText' => trans('restaurant.update_menu')])
        {!! Form::close() !!}
    </div>
@stop

@extends('restaurant.index')

@section('content')

    <div class="restaurant-category">
        <div class="restaurant-category__header">

            @include('components.back', ['text' => 'Back'])

        </div>

        {{ Form::model($category = new \App\MenuCategory, ['action' => 'MenuCategoriesController@store', 'method' => 'POST', 'files' => true]) }}
        @include('restaurant.menu_categories._form', ['submitButtonText' => trans('restaurant.create_category')])
        {{ Form::close() }}

    </div>

@stop

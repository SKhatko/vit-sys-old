@extends('restaurant.index')

@section('content')

    <div class="restaurant-category">
        @if (count($menuLanguages))

            <div class="restaurant-category__header">

                @include('components.back')

            </div>

            {{ Form::model($category, ['action' => ['MenuCategoriesController@update', $category->id], 'method' => 'PATCH', 'files' => true]) }}
            @include('restaurant.menu_categories._form', ['submitButtonText' => trans('restaurant.update_category')])
            {{ Form::close() }}

        @else

            @include('partials.error', [
      'message' => trans('restaurant.add_languages_first_error'),
      'action' => action('ConfigController@onlineMenu'),
      'actionName' => 'Settings'
       ])

        @endif

    </div>

@stop

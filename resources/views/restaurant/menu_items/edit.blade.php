@extends('restaurant.index')

@section('content')

    <div class="restaurant-item">
        @if (count($menuLanguages))

            <div class="restaurant-item__header">
                @include('components.back', ['text' => 'Back'])
            </div>

            {{ Form::model($item, ['action' => ['MenuItemsController@update', $item->id], 'method' => 'PATCH', 'files' => true, 'id' => 'item-data-form']) }}
            @include('restaurant.menu_items._form', ['submitButtonText' => trans('general.update')])
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

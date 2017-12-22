@extends('restaurant.index')

@section('header-print')
    @include('components.print_button')
@stop

@section('content')
    <div class="restaurant-sections">

        {{ Form::model($section = new \App\Section, ['action' => 'SectionsController@store', 'method' => 'POST']) }}

        <div class="restaurant-sections__create">

            <div class="restaurant-sections__create--name">
                {{ Form::label('name', trans('restaurant.section_name'), ['class' => 'label']) }}
                {{ Form::text('name', NULL, ['class' => 'input', 'required', 'placeholder' => 'Name...' , 'autofocus' => 'autofocus']) }}
            </div>

            <div class="restaurant-sections__create--desc">
                {{ Form::label('description', trans('general.description'), ['class' => 'label']) }}
                {{ Form::text('description', NULL, ['class' => 'input', 'placeholder' => 'Description...' , 'required']) }}
            </div>

            {{ Form::button(trans('general.create'), ['type'=>'submit', 'class' => 'restaurant-sections__create--submit']) }}

        </div>


        {{ Form::close() }}

        @if(count($sections))
            <div class="restaurant-sections__list">
                <div class="restaurant-sections__list-header">
                    <div title="Section name" class="restaurant-sections__list-header--name">
                        Section name
                    </div>

                    <div title="Description" class="restaurant-sections__list-header--desc">
                        Description
                    </div>

                    <div title="{{ trans('general.edit')  }}" class="restaurant-sections__list-header--edit">
                        {{ trans('general.edit') }}
                    </div>

                    <div title="{{ trans('general.delete') }}" class="restaurant-sections__list-header--delete">
                        {{ trans('general.delete') }}
                    </div>

                </div>

                <div class="js-restaurant-sections__list-content">
                    @foreach ($sections as $section)
                        <div data-section="{{ $section->id }}" id="{{ $section->id }}"
                             class="restaurant-sections__list-item js-restaurant-sections__list-item">

                            <svg class="restaurant-sections__list-item-icon js-restaurant-sections__list-item-icon">
                                <use xlink:href="#icon-drag"></use>
                            </svg>

                            <div title="{{ $section->name }}" class="restaurant-sections__list-item--name js-restaurant-sections__list-item--name">
                                {{ $section->name }}
                            </div>

                            <div title="{{ $section->description }}"
                                 class="restaurant-sections__list-item--desc js-restaurant-sections__list-item--desc">
                                {{ $section->description }}
                            </div>

                            <div class="restaurant-sections__list-item--edit">
                                <svg class="restaurant-sections__list-item--edit-icon js-restaurant-sections__list-item--edit">
                                    <use xlink:href="#icon-edit"></use>
                                </svg>
                            </div>

                            <div class="restaurant-sections__list-item--delete">
                                <svg class="restaurant-sections__list-item--delete-icon js-restaurant-sections__list-item--delete">
                                    <use xlink:href="#icon-cross"></use>
                                </svg>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@stop

@section('helpers')
    <!----- edit ITEM MODAL ------>
    @component('components.modal', ['class' => 'restaurant-sections__modal-edit js-restaurant-sections__modal-edit'])

        {{ Form::open(['method' => 'PATCH', 'action' => 'SectionsController@index']) }}

        <div class="restaurant-sections__modal-edit--title">
            {{ trans('restaurant.edit_section') }}
        </div>

        {{ Form::label('name', trans('restaurant.section_name'), ['class' => 'restaurant__label']) }}
        {{ Form::text('name', NULL, ['class' => 'restaurant__input js-restaurant-sections__modal--name', 'required', 'autofocus' => 'autofocus']) }}

        {{ Form::label('description', trans('general.description'), ['class' => 'restaurant__label']) }}
        {{ Form::textarea('description', NULL, ['class' => 'restaurant__input js-restaurant-sections__modal--desc']) }}

        {{ Form::button(trans('restaurant.update_section'), ['type'=>'submit', 'class' => 'restaurant-sections__modal-edit--submit']) }}

        {{ Form::close() }}

    @endcomponent

    <!----- DELETE ITEM MODAL ------>
    @component('components.modal', ['class' => 'restaurant-sections__modal-delete js-restaurant-sections__modal-delete'])

        {{ Form::open(['method' => 'DELETE', 'action' => 'SectionsController@index']) }}

        <div class="restaurant-sections__modal-delete--title">
            {{ trans('restaurant.delete_section') }}
        </div>

        <div class="restaurant-sections__modal-delete--content">
            {{ trans('restaurant.delete_section_confirmation_msg') }}
        </div>

        {{ Form::button(trans('general.confirm'), ['type'=>'submit','class' => 'restaurant-sections__modal-delete--submit']) }}
        {{ Form::close() }}

    @endcomponent
@stop

@section('script')
    <script>
        new Restaurant().sections();
    </script>
@stop
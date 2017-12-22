@extends('restaurant.index')

@section('header-print')
    @include('components.print_button')
@stop

@section('content')

    <div class="restaurant-categories">

        <div class="restaurant-categories__create">
            <a href="{{ action('MenuCategoriesController@create') }}"
               class="restaurant-categories__create--button">{{ trans('restaurant.new_category') }}</a>
        </div>

        <div class="restaurant-categories__header">

            <div title="Category Name" class="restaurant-categories__header--name">
                Category Name
            </div>

            <div title="Categories" class="restaurant-categories__header--categories">
                Categories
            </div>

            <div title="Dishes" class="restaurant-categories__header--dishes">
                Dishes
            </div>

            <div title="{{ trans('general.edit') }}" class="restaurant-categories__header--edit">
                {{ trans('general.edit') }}
            </div>

            <div title="{{ trans('general.delete') }}" class="restaurant-categories__header--delete">
                {{ trans('general.delete') }}
            </div>

        </div>

        <ul class="restaurant-categories__content js-restaurant-categories__content">
            @while (count($categoriesTree))
                @php
                        $item = array_shift($categoriesTree);
                @endphp

            @if (is_array($item) && count($item['children']))
                    @php
                            array_unshift($categoriesTree, null);
                            $categoriesTree = array_merge($item['children'], $categoriesTree);
                    @endphp

                    <li id="item_{{ $item['object']->id }}"
                        class="restaurant-categories__content-item  js-restaurant-categories__content-item">
                        <div data-category-id="{{ $item['object']->id }}"
                             data-items-count="{{ $item['object']->itemsCount }}"
                             title="{{  $item['object']->translatedName( $language ) }}"
                             class="restaurant-categories__content-item-wrapper js-restaurant-categories__content-item-wrapper">
                            <svg class="restaurant-categories__content-item-wrapper--icon js-restaurant-categories__content-item-wrapper--icon">
                                <use xlink:href="#icon-drag"></use>
                            </svg>

                            <div class="restaurant-categories__content-item-wrapper--name js-restaurant-categories__content-item-wrapper--name">

                                <div class="restaurant-categories__content-item-wrapper--name-link">
                                    {{  $item['object']->translatedName( $language ) }}
                                </div>

                                <svg class="restaurant-categories__content-item-wrapper--name-icon">
                                    <use xlink:href="#icon-arrow-down"></use>
                                </svg>
                            </div>

                            <div class="restaurant-categories__content-item-wrapper--categories js-restaurant-categories__content-item-wrapper--categories">
                                {{ count($item['children']) }}
                            </div>

                            <div class="restaurant-categories__content-item-wrapper--dishes">
                                @if($item['object']->itemsCount)
                                    {{ $item['object']->itemsCount }}
                                @else
                                    <a href="{{ action('MenuItemsController@create', [$item['object']->id]) }}"
                                       class="restaurant-categories__content-item-wrapper--dishes-link">
                                        <svg class="restaurant-categories__content-item-wrapper--dishes-link-icon">
                                            <use xlink:href="#icon-thin-cross"></use>
                                        </svg>
                                    </a>
                                @endif
                            </div>

                            <a href="{{ action('MenuCategoriesController@edit', [$item['object']->id]) }}"
                               class="restaurant-categories__content-item-wrapper--edit">
                                <svg class="restaurant-categories__content-item-wrapper--edit-icon">
                                    <use xlink:href="#icon-edit"></use>
                                </svg>
                            </a>

                            <div class="restaurant-categories__content-item-wrapper--delete">
                                <svg class="restaurant-categories__content-item-wrapper--delete-icon js-restaurant-categories__content-item-wrapper--delete-icon">
                                    <use xlink:href="#icon-cross"></use>
                                </svg>

                                {{--<a href="javascript:;" onclick="deleteCategory('{{ $item['object']->id }}', '{{ $item['object']->translatedName($language) }}', '{{ $item['object']->itemsCount }}', '{{ count($item['children']) }}');">{{ trans('general.delete') }}</a>--}}
                            </div>

                        </div>

                        <ul class="restaurant-categories__content-item-sub js-restaurant-categories__content-item-sub">

                            @elseif (is_null($item))

                        </ul>
                    </li>

                @else

                    <li id="item_{{ $item['object']->id }}"
                        class="restaurant-categories__content-item empty js-restaurant-categories__content-item">
                        <div data-category-id="{{ $item['object']->id }}"
                             data-items-count="{{ $item['object']->itemsCount }}"
                             title=" {{ $item['object']->translatedName($language) }} "
                             class="restaurant-categories__content-item-wrapper js-restaurant-categories__content-item-wrapper">
                            <svg class="restaurant-categories__content-item-wrapper--icon js-restaurant-categories__content-item-wrapper--icon">
                                <use xlink:href="#icon-drag"></use>
                            </svg>

                            <div class="restaurant-categories__content-item-wrapper--name">

                                <div class="restaurant-categories__content-item-wrapper--name-link">
                                    {{ $item['object']->translatedName($language) }}
                                </div>

                                <svg class="restaurant-categories__content-item-wrapper--name-icon">
                                    <use xlink:href="#icon-arrow-down"></use>
                                </svg>
                            </div>

                            <div class="restaurant-categories__content-item-wrapper--categories js-restaurant-categories__content-item-wrapper--categories">
                                {{ $item['object']->itemsCount }}
                            </div>

                            <div class="restaurant-categories__content-item-wrapper--dishes">
                                @if($item['object']->itemsCount)
                                    {{ $item['object']->itemsCount }}
                                @else
                                    <a href="{{ action('MenuItemsController@create', [$item['object']->id]) }}"
                                       class="restaurant-categories__content-item-wrapper--dishes-link">
                                        <svg class="restaurant-categories__content-item-wrapper--dishes-link-icon">
                                            <use xlink:href="#icon-thin-cross"></use>
                                        </svg>
                                    </a>
                                @endif
                            </div>

                            <a href="{{ action('MenuCategoriesController@edit', [$item['object']->id]) }}"
                               class="restaurant-categories__content-item-wrapper--edit">
                                <svg class="restaurant-categories__content-item-wrapper--edit-icon">
                                    <use xlink:href="#icon-edit"></use>
                                </svg>
                            </a>

                            <div class="restaurant-categories__content-item-wrapper--delete">
                                <svg class="restaurant-categories__content-item-wrapper--delete-icon js-restaurant-categories__content-item-wrapper--delete-icon">
                                    <use xlink:href="#icon-cross"></use>
                                </svg>

                                {{--<a href="javascript:;" onclick="deleteCategory('{{ $item['object']->id }}', '{{ $item['object']->translatedName($language) }}', '{{ $item['object']->itemsCount }}', '{{ count($item['children']) }}');">{{ trans('general.delete') }}</a>--}}
                            </div>

                        </div>

                    </li>

                @endif
            @endwhile

        </ul>

    </div>

@stop

@section('helpers')

    <!----- DELETE ITEM MODAL ------>
    @component('components.modal', ['class' => 'restaurant-categories__modal-delete js-restaurant-categories__modal-delete'])

        {{ Form::open(['method' => 'DELETE', 'action' => 'MenuCategoriesController@index']) }}

        <div class="restaurant-categories__modal-delete--title">
            {{ trans('restaurant.delete_category') }}
        </div>

        <div class="restaurant-categories__modal-delete--content js-restaurant-categories__modal-delete--content">
            {{ trans('restaurant.delete_section_confirmation_msg') }}
        </div>

        {{ Form::submit(trans('general.confirm'), ['class' => 'restaurant-categories__modal-delete--submit js-restaurant-categories__modal-delete--submit']) }}
        {{ Form::close() }}

    @endcomponent

@stop

@section('script')
    <script>

        new Restaurant({
            msgContainsItems: '{{ trans('restaurant.category_contains_items_delete_error') }}',
            msgContainsCategories: '{{ trans('restaurant.category_contains_sub_categories_delete_error') }}',
            msgConfirmCategory: '{{ trans('restaurant.confirmation_category_delete_message_with_name') }}'
        }).menuCategories();

    </script>
@stop
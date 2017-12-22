@extends('restaurant.index')

@section('header-print')
    @include('components.print_button')
@stop

@section('content')

    @if(count($categoriesTree))

        <div class="restaurant-items">
            <div class="restaurant-items-header">

                <div class="restaurant-items-header-left">

                    <div class="restaurant-items-header__select">
                        {{ Form::open(['method' => 'POST', 'action' => 'MenuItemsController@filter']) }}
                        {{ Form::label('category_id', trans('restaurant.filter_by_category'), ['class' => 'restaurant__label']) }}
                        {{ \App\Misc::renderTreeSelect('category_id', $categoriesTree, [$filterCategoryId], false, true, ['class' => 'select js-restaurant-items-header__select-category'], trans('general.all')) }}
                        {{ Form::close() }}
                    </div>

                    <a href="{{ action('MenuGroupsController@index') }}" class="restaurant-items-header__add">
                        <svg class="restaurant-items-header__add--icon">
                            <use xlink:href="#icon-cross"></use>
                        </svg>
                    </a>

                    <div class="restaurant-items-header__select-all">
                        <div class="restaurant-items-header__select-all--checkbox">
                            {{ Form::checkbox('select-all', false, false, ['class' => 'js-restaurant-items-header__select-all--checkbox', 'id' => 'select-all']) }}
                            <label for="select-all"></label>
                        </div>

                        {{ Form::label('select-all',  trans('general.select_all'), ['class' => 'restaurant-items-header__select-all--label']) }}
                    </div>

                    <div class="restaurant-items-header-move js-restaurant-items-header-move">
                        <div class="restaurant-items-header__select">
                            {{ Form::label('move-to-category', trans('restaurant.change_category'), ['class' => 'restaurant__label']) }}
                            {{ \App\Misc::renderTreeSelect('category_id', $categoriesTree, [$filterCategoryId], false, false, ['class' => 'select js-restaurant-items-header__move-category', 'id' => 'category-move-to'], trans('general.select')) }}
                            {{--                            {{ \App\Misc::renderTreeSelect('category_id', $categoriesTree, [$filterCategoryId], false, false, ['class' => 'form-control', 'id' => 'move-category-sel'], NULL);--}}
                        </div>

                        <a href="javascript:"
                           class="restaurant-items-header__remove-item js-restaurant-items-header__remove-item">
                            <svg class="restaurant-items-header__remove-item--icon">
                                <use xlink:href="#icon-cross"></use>
                            </svg>
                        </a>
                    </div>

                </div>

                <div class="restaurant-items-header__view">
                    <div class="restaurant-items-header__view--label">
                        View dishes
                    </div>

                    <div class="restaurant-items-header__view-buttons">
                        <a href="javascript:"
                           class="restaurant-items-header__view-block active js-restaurant-items-header__view-block">
                            <svg class="restaurant-items-header__view-block--icon">
                                <use xlink:href="#icon-tiles-view"></use>
                            </svg>
                        </a>

                        <a href="javascript:"
                           class="restaurant-items-header__view-list js-restaurant-items-header__view-list">
                            <svg class="restaurant-items-header__view-list--icon">
                                <use xlink:href="#icon-list-view"></use>
                            </svg>
                        </a>

                    </div>
                </div>

            </div>

            {{ Form::open(['method' => 'POST', 'action' => 'MenuItemsController@multipleItemsAction', 'class' => 'js-restaurant-items__form']) }}
            {{ Form::hidden('action', null, ['class' => 'js-restaurant-items__action']) }}
            {{ Form::hidden('category_id', null, ['class' => 'js-restaurant-items__category']) }}

            <div class="restaurant-items-content blocks js-restaurant-items-content">

                <div class="restaurant-items-content__header">
                    <div class="restaurant-items-content__header--select">
                        Select
                    </div>
                    <div class="restaurant-items-content__header--name">
                        Name
                    </div>
                    <div class="restaurant-items-content__header--description">
                        Description
                    </div>
                    <div class="restaurant-items-content__header--price">
                        Price
                    </div>
                    <div class="restaurant-items-content__header--edit">
                        Edit
                    </div>
                    <div class="restaurant-items-content__header--delete">
                        Delete
                    </div>
                </div>

                <div class="restaurant-items-content__new">
                    <div class="restaurant-items-content__new-item">
                        <a href="{{ action('MenuItemsController@create') }}"
                           class="restaurant-items-content__new-item-link">
                            <svg class="restaurant-items-content__new-item-link--icon">
                                <use xlink:href="#icon-thin-cross"></use>
                            </svg>
                        </a>

                        <div class="restaurant-items-content__new-item--label">
                            {{ trans('restaurant.add_item') }}
                        </div>
                    </div>
                </div>

                @if(count($items))
                    @foreach ($items as $item)

                        <div class="restaurant-items-content__item js-restaurant-items-content__item"
                             data-item="{{ $item->id }}"
                             id="item{{ $loop->index }}_{{ $item->id }}">
                            <svg class="restaurant-items-content__item--sort js-restaurant-items-content__item--sort">
                                <use xlink:href="#icon-drag-long"></use>
                            </svg>

                            <div class="restaurant-items-content__item-img">
                                <!-- viewBox(-7 -7 25 25) -->
                                <img src="{{ $item->image ? $item->image : asset('/images/no-photo.svg') }}"
                                     alt="{{ $item->name ? $item->name : trans('general.no_image') }}"
                                     class="restaurant-items-content__item-img--image">
                            </div>

                            <div class="restaurant-items-content__item-select">
                                <div class="restaurant-items-content__item-select--checkbox">
                                    {{ Form::checkbox( 'item_selection[]', $item->id, false, ['id' => 'item_' . $item->id , 'class' => 'js-restaurant-items-content__item-select--checkbox']) }}
                                    <label for="{{ 'item_' . $item->id }}"></label>
                                </div>
                            </div>

                            <div class="restaurant-items-content__item--name js-restaurant-items-content__item--name"
                                 title="{{ $item->translatedName($language) }}">
                                {{ $item->translatedName($language) }}
                            </div>

                            <div class="restaurant-items-content__item--description"
                                 title="{{ $item->translatedDescription($language) }}">
                                {{ str_limit($item->translatedDescription($language), $limit = 54, $end = '...') }}
                            </div>

                            <div class="restaurant-items-content__item--price">
                                {{ \App\Misc::printCurrency() . ' ' . \App\Misc::formatDecimal($item->price) }}
                            </div>

                            <div class="restaurant-items-content__item--edit">
                                <a href="{{ action('MenuItemsController@edit', $item->id) }}"
                                   title="Edit"
                                   class="restaurant-items-content__item--edit-link">
                                    <svg class="restaurant-items-content__item--edit-icon">
                                        <use xlink:href="#icon-edit"></use>
                                    </svg>
                                </a>
                            </div>

                            <div class="restaurant-items-content__item--delete">
                                <svg class="restaurant-items-content__item--delete-icon js-restaurant-items-content__item--delete-icon">
                                    <use xlink:href="#icon-cross"></use>
                                </svg>
                            </div>
                        </div>

                    @endforeach
                @endif

            </div>

            {{ Form::close()  }}

        </div>

    @else

        @include('partials.error', [
        'message' => trans('restaurant.add_categories_before_items_warning_msg'),
        'action' => action('MenuCategoriesController@index'),
        'actionName' => trans('restaurant.menu_categories')
         ])

    @endif
@stop

@section('helpers')

    <!----- DELETE ITEM MODAL ------>
    @component('components.modal', ['class' => 'restaurant-items__modal-delete js-restaurant-items__modal-delete'])

        {{ Form::open(['method' => 'DELETE', 'action' => 'MenuItemsController@index']) }}

        <div class="restaurant-items__modal-delete--title">
            {{ trans('restaurant.delete_item') }}
        </div>

        <div class="restaurant-items__modal-delete--content">
            {{ trans('restaurant.confirmation_menu_item_delete_message_with_name') }}
            <span class="restaurant-items__modal-delete--name js-restaurant-items__modal-delete--name"></span>?
        </div>

        {{ Form::button(trans('general.confirm'), ['type'=>'submit','class' => 'restaurant-items__modal-delete--submit']) }}
        {{ Form::close() }}

    @endcomponent

    <!----- DELETE MULTIPLE ITEMS MODAL ------>
    @component('components.modal', ['class' => 'restaurant-items__modal-delete-selected js-restaurant-items__modal-delete-selected'])

        <div class="restaurant-items__modal-delete--title">
            {{ trans('restaurant.delete_menu_items') }}
        </div>

        <div class="restaurant-items__modal-delete--content">
            {{ trans('restaurant.confirmation_menu_items_delete_message_with_number') }}
        </div>

        {{ Form::button(trans('general.confirm'), ['class' => 'restaurant-items__modal-delete--submit js-restaurant-items__modal-delete--submit']) }}

    @endcomponent

    <!----- CHANGE CATEGORY MODAL ------>
    @component('components.modal', ['class' => 'restaurant-items__modal-move js-restaurant-items__modal-move'])


        <div class="restaurant-items__modal-move--title">
            {{ trans('restaurant.change_category') }}
        </div>

        <div class="restaurant-items__modal-move--content">
            {{ trans('restaurant.confirmation_menu_items_move_message_with_number') }}
            <span class="restaurant-items__modal-move--name js-restaurant-items__modal-move--name"></span>?
        </div>

        {{ Form::button(trans('general.confirm'), ['class' => 'restaurant-items__modal-move--submit js-restaurant-items__modal-move--submit']) }}

    @endcomponent

@stop

@section('script')
    <script>
        new Restaurant().menuItems();
    </script>
@stop
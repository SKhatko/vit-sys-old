@extends('restaurant.index')

@section('header-print')
    @include('components.print_button')
@stop

@section('content')

    <div class="restaurant-menus">
        <div class="restaurant-menus__top">
            <a href="{{ action('CustomMenusController@create') }}" class="restaurant-menus__top--create">
                {{ trans('general.create') }}
            </a>
        </div>

        @if (count($customMenus))

            <div class="restaurant-menus__header">
                <div title="Preorder Menus Name" class="restaurant-menus__header--name">
                    Preorder Menus Name
                </div>

                <div title="Menus" class="restaurant-menus__header--menus">
                    Menus
                </div>

                <div title="Dishes" class="restaurant-menus__header--dishes">
                    Dishes
                </div>

                <div class="restaurant-menus__header--edit" title="{{ trans('general.edit') }}">
                    {{ trans('general.edit') }}
                </div>

                <div class="restaurant-menus__header--delete" title="{{ trans('general.delete') }}">
                    {{ trans('general.delete') }}
                </div>

            </div>

            <div class="restaurant-menus__content js-restaurant-menus__content">

                @foreach ($customMenus as $customMenu)

                    <div data-menu="{{ $customMenu->id }}" id="menu{{ $loop->index }}_{{ $customMenu->id }}"
                         class="restaurant-menus__group js-restaurant-menus__group">

                        <svg class="restaurant-menus__group--icon js-restaurant-menus__group--icon">
                            <use xlink:href="#icon-drag"></use>
                        </svg>

                        <div class="restaurant-menus__group--name js-restaurant-menus__group--name"
                             title="{{ $customMenu->name }}">
                            <!-- TODO translatedName($language) -->
                            {{ $customMenu->name }}
                        </div>

                        <div class="restaurant-menus__group--menus">
                                {{ $customMenu->menu_groups->count() }}
                        </div>

                        <div class="restaurant-menus__group--dishes">
                            {{ $customMenu->items->count() }}
                        </div>

                        <a href="{{ action('CustomMenusController@edit', [$customMenu->id]) }}"
                           class="restaurant-menus__group--edit" title="{{ trans('general.edit') }}">
                            <svg class="restaurant-menus__group--edit-icon ">
                                <use xlink:href="#icon-edit"></use>
                            </svg>
                        </a>

                        <div class="restaurant-menus__group--delete">
                            <svg class="restaurant-menus__group--delete-icon js-restaurant-menus__group--delete">
                                <use xlink:href="#icon-cross"></use>
                            </svg>
                        </div>

                        {{--- {{ \App\Misc::formatDecimal($group->price) }}{{ \App\Misc::printCurrency() }}--}}
                        {{--                        {{ '(' . $group->courses_count . ' ' . trans_choice('restaurant.courses_for_count', $group->courses_count) . ', ' . $group->getItemsCount() . ' ' . trans_choice('restaurant.items_for_count', $group->getItemsCount()) . ')' }}--}}


                    </div>

                @endforeach

            </div>

        @endif

    </div>

@stop

@section('helpers')

    <!----- DELETE table plan MODAL ------>
    @component('components.modal', ['class' => 'restaurant-menus__modal-delete js-restaurant-menus__modal-delete'])

        {{ Form::open(['method' => 'DELETE', 'action' => 'CustomMenusController@index']) }}

        <div class="restaurant-menus__modal-delete--title">
            {{ trans('restaurant.delete_menu') }}
        </div>

        <div class="restaurant-menus__modal-delete--content">
            {!! trans('restaurant.confirmation_menu_delete_message_with_name', ['name' => '<strong><span class="js-restaurant-menus__modal-delete--content-name"></span></strong>']) !!}
            ?
        </div>

        {{ Form::button(trans('general.confirm'), ['type' => 'submit','class' => 'restaurant-menus__modal-delete--submit']) }}

        {{ Form::close() }}

    @endcomponent

@stop

@section('script')
    <script>
        new Restaurant().menus();
    </script>
@stop
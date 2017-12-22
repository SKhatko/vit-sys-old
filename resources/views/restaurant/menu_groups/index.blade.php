@extends('restaurant.index')

@section('header-print')
    @include('components.print_button')
@stop

@section('content')

    <div class="restaurant-groups">
        <div class="restaurant-groups__top">
            <a href="{{ action('MenuGroupsController@create') }}" class="restaurant-groups__top--create">
                {{ trans('general.create') }}
            </a>
        </div>

        <div class="restaurant-groups__header">
            <div title="Menus Name" class="restaurant-groups__header--name">
                Menus Name
            </div>

            <div title="Description" class="restaurant-groups__header--description">
                Description
            </div>

            <div title="Courses" class="restaurant-groups__header--courses">
                Courses
            </div>

            <div title="Dishes" class="restaurant-groups__header--dishes">
                Dishes
            </div>

            <div class="restaurant-groups__header--edit" title="{{ trans('general.edit') }}">
                {{ trans('general.edit') }}
            </div>

            <div class="restaurant-groups__header--delete" title="{{ trans('general.delete') }}">
                {{ trans('general.delete') }}
            </div>

        </div>

        <div class="restaurant-groups__content js-restaurant-groups__content">

            @if ( count( $groups ) )

                @foreach ($groups as $group)

                    <div data-group="{{ $group->id }}" id="group{{ $loop->index }}_{{ $group->id }}"
                         class="restaurant-groups__group js-restaurant-groups__group">

                        <svg class="restaurant-groups__group--icon js-restaurant-groups__group--icon">
                            <use xlink:href="#icon-drag"></use>
                        </svg>

                        <div class="restaurant-groups__group--name js-restaurant-groups__group--name"
                             title="{{ $group->translatedName($language) }}">
                            {{ $group->translatedName( $language ) }} -
                            ( {{ \App\Misc::formatDecimal($group->price) }}{{ \App\Misc::printCurrency() }} )
                        </div>

                        <div title="{{ $group->description }}" class="restaurant-groups__group--description">
                            {{ $group->description }}
                        </div>

                        <div class="restaurant-groups__group--courses">
                            {{ $group->courses_count }}
                        </div>

                        <div class="restaurant-groups__group--dishes">
                            {{ $group->getItemsCount() }}
                        </div>

                        <a href="{{ action('MenuGroupsController@edit', [$group->id]) }}"
                           class="restaurant-groups__group--edit" title="{{ trans('general.edit') }}">
                            <svg class="restaurant-groups__group--edit-icon ">
                                <use xlink:href="#icon-edit"></use>
                            </svg>
                        </a>

                        <div class="restaurant-groups__group--delete">
                            <svg class="restaurant-groups__group--delete-icon js-restaurant-groups__group--delete">
                                <use xlink:href="#icon-cross"></use>
                            </svg>
                        </div>


                    </div>

                    {{--                        {{ '(' . $group->courses_count . ' ' . trans_choice('restaurant.courses_for_count', $group->courses_count) . ', ' . $group->getItemsCount() . ' ' . trans_choice('restaurant.items_for_count', $group->getItemsCount()) . ')' }}--}}

                @endforeach

            @endif

        </div>

    </div>

@stop

@section('todo')

    {!! Form::open(['method' => 'post', 'action' => 'OnlineMenuController@setMenusBackgroundImage', 'id' => 'background-image-form']) !!}

    <h4>{{ trans('restaurant.menus_background') }}</h4>

    <div class="form-group">
        @if (!$settings['menus_background'])
            <span color="orange">{{ trans('restaurant.no_image_selected') }}</span>
        @else
            <img src="{{ \App\Misc::getResizedAlbumImage(url($settings['menus_background']), 'thumb') }}"
                 style="width:200px; max-width:100%;"/>
        @endif

        <br><br>
        <input type="hidden" name="background_image" id="menu-image-input"
               value="{{ $settings['menus_background'] }}"/>
        <a href="javascript:;" id="photo-album-btn">{{ trans('menu.change_photo') }}</a>
    </div>

    <div id="change-background-loader" style="display:none;"><img src="{{ asset('img/ajax-loader.gif') }}"
                                                                  alt="{{ trans('general.loading') }}..."
                                                                  style="margin-bottom:10px;"/></div>

    {!! Form::close() !!}

    <hr>

    <h4>{{ trans('restaurant.menus_page_title') }}</h4>

    {!! Form::open(['method' => 'post', 'action' => 'OnlineMenuController@setMenuTitleTranslations']) !!}
    @foreach ($menuLanguages as $menuLanguage)
        <?php
        $lang = $menuLanguage->language;
        ?>
        <div class="form-group">
            {!! Form::label('translations['.$lang.']', trans('languages.'.$lang)) !!}
            {!! Form::text('translations['.$lang.']', \App\MenuSingleton::getInstance()->menu_title($lang), ['class' => 'form-control', 'required']) !!}
        </div>

    @endforeach

    <div class="form-group">
        {!! Form::submit(trans('general.submit'), ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}

@stop

@section('helpers')

    <!----- DELETE table plan MODAL ------>
    @component('components.modal', ['class' => 'restaurant-groups__modal-delete js-restaurant-groups__modal-delete'])

        {{ Form::open(['method' => 'DELETE', 'action' => 'MenuGroupsController@index']) }}

        <div class="restaurant-groups__modal-delete--title">
            {{ trans('restaurant.delete_menu') }}
        </div>

        <div class="restaurant-groups__modal-delete--content">
            {!! trans('restaurant.confirmation_menu_delete_message_with_name', ['name' => '<strong><span class="js-restaurant-groups__modal-delete--content-name"></span></strong>']) !!}
            ?
        </div>

        {{ Form::button(trans('general.confirm'), ['type' => 'submit','class' => 'restaurant-groups__modal-delete--submit']) }}

        {{ Form::close() }}

    @endcomponent

@stop

@section('script')
    <script>
        new Restaurant().menuGroups();
    </script>
@stop
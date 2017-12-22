<div class="restaurant-group__top">

    <div class="restaurant-group__top--name">
        {{ Form::label('names[' . $language .']',  trans('restaurant.menu_name') . ' (' . trans('languages.'.$language) . ')', ['class' => 'label']) }}
        {{ Form::text('names[' . $language .']', $group && $group->translatedName($language) ? $group->translatedName($language) : NULL , ['class' => 'input']) }}

        <a href="javascript:" class="restaurant-group__top-link js-restaurant-group__top-link">
            More languages
            <svg class="restaurant-group__top-link--icon">
                <use xlink:href="#icon-arrow-down"></use>
            </svg>
        </a>
    </div>

    <div class="restaurant-group__top--price">
        {!! Form::label('price', trans('restaurant.price'), ['class' => 'label']) !!}
        {!! Form::text('price', $group->price ?? $group->price, ['class' => 'input', 'required']) !!}
    </div>

    <div class="restaurant-group__top--description">
        {{ Form::label('descriptions[' . $language . ']', trans('general.description'), ['class' => 'label']) }}
        {{ Form::text('descriptions[' . $language . ']', $group && $group->translatedDescription($language) ? $group->translatedDescription($language) : NULL, ['class' => 'input']) }}
    </div>

</div>

<div class="restaurant-group__create">
    <div class="restaurant-group__create-link js-restaurant-group__create-link">
        <svg class="restaurant-group__create-link--icon">
            <use xlink:href="#icon-cross"></use>
        </svg>
    </div>

    <div class="restaurant-group__create--label">
        {{ trans('restaurant.add_course') }}
    </div>
</div>

<div class="restaurant-group__content js-restaurant-group__content {{ count($group->courses) ? 'active' : '' }}">
    <div class="restaurant-group__content-courses js-restaurant-group__content-courses">

        @if(count($group->courses))
            @foreach($group->courses as $key => $course)
                <div class="restaurant-group__course js-restaurant-group__course">

                    <div class="restaurant-group__course-header">
                        <div class="restaurant-group__course-header-link js-restaurant-group__course-header-link">
                            <div class="restaurant-group__course-header--name">
                                Course
                            </div>
                            <div class="restaurant-group__course-header--number js-restaurant-group__course-header--number">
                                {{ $key + 1 }}
                            </div>
                            <svg class="restaurant-group__course-header--icon">
                                <use xlink:href="#icon-arrow-down"></use>
                            </svg>
                        </div>

                        <svg class="restaurant-group__course-header--remove js-restaurant-group__course-header--remove">
                            <use xlink:href="#icon-cross"></use>
                        </svg>
                    </div>

                    <div class="restaurant-group__course-content">

                        {{ Form::label('quantities[' . $key . ']', trans('restaurant.course_quantity'), ['class' => 'label']) }}
                        {{ Form::number('quantities[' . $key . ']', $course->quantity, ['class' => 'restaurant-group__course-content--quantity js-restaurant-group__course-content--quantity', 'min' => 1]) }}

                        <div class="restaurant-group__course-content-items js-restaurant-group__course-content-items">
                            @foreach($course->menu_items as $menuItem)
                                <div class="restaurant-group__course-content-item js-restaurant-group__course-content-item">
                                    {{ Form::hidden('items[' . $key . ']', $menuItem->id, ['class' => 'js-restaurant-group__course-content-item--hidden']) }}

                                    <div class="restaurant-group__course-content-item--name js-restaurant-group__course-content-item--name">
                                        {{ $menuItem->translatedName($language) }}
                                    </div>

                                    <svg class="restaurant-group__course-content-item--remove js-restaurant-group__course-content-item--remove">
                                        <use xlink:href="#icon-cross"></use>
                                    </svg>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    </div>

    <div class="restaurant-group__content-categories">

        <div class="restaurant-group__category {{ count($group->courses) ? 'active' : ''}}">

            <div class="restaurant-group__category-header">
                {{ trans('restaurant.menu_categories') }}
            </div>

            <div class="restaurant-group__category-content">

                <div class="restaurant-group__category-content-top">
                {{ \App\Misc::renderTreeSelect('category', $categoriesTree, [null], false, true, ['class' => 'restaurant-group__category-content--select js-restaurant-group__category-content--select'], trans('general.all')) }}

                <!-- TODO search -->
                    {{--                    {{ Form::text('search', null, ['class' => 'restaurant-group__category-content--search']) }}--}}

                </div>

                <div class="restaurant-group__category-content-items">
                    @foreach($items as $item)

                        <div class="restaurant-group__category-content-item js-restaurant-group__category-content-item"
                             data-category-id="{{ $item->category_id }}" data-item-id="{{ $item->id }}">
                            <div class="restaurant-group__category-content-item-img">
                                <!-- viewBox(-7 -7 25 25) -->
                                <img src="{{ $item->image ? $item->image : asset('/images/no-photo.svg') }}"
                                     alt="{{ $item->name ? $item->translatedName($language) : trans('general.no_image') }}"
                                     class="restaurant-group__category-content-item-img--image">
                            </div>

                            <a href="javascript:" class="restaurant-group__category-content-item--add js-restaurant-group__category-content-item--add">
                                <svg class="restaurant-group__category-content-item--add-icon">
                                    <use xlink:href="#icon-cross"></use>
                                </svg>
                            </a>

                            <div class="restaurant-group__category-content-item--name js-restaurant-group__category-content-item--name"
                                 title="{{ $item->translatedName($language) }}">
                                {{ $item->translatedName($language) }}
                            </div>

                            <div class="restaurant-group__category-content-item--description"
                                 title="{{ $item->translatedDescription($language) }}">
                                {{ $item->translatedDescription($language) }}
                            </div>

                            <div class="restaurant-group__category-content-item--price">
                                {{ \App\Misc::printCurrency() . ' ' . \App\Misc::formatDecimal($item->price) }}
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        </div>
        <div class="restaurant-group__bottom">
            <div class="restaurant-group__bottom-checkboxes">
                <div class="restaurant-group__bottom-online">
                    <div class="restaurant-group__bottom--checkbox">
                        {{ Form::checkbox('online_shown', true, $group && $group->online_shown, ['id' => 'online_shown'] ) }}
                        <label for="online_shown"></label>
                    </div>
                    {{ Form::label('online_shown', trans('restaurant.show_in_online_menu'), ['class' => 'restaurant-group__bottom--label']) }}
                </div>
                <div class="restaurant-group__bottom-preorders">
                    <div class="restaurant-group__bottom--checkbox">
                        {{ Form::checkbox('preorders_shown', true, $group && $group->preorders_shown, ['id' => 'preorders_shown'] ) }}
                        <label for="preorders_shown"></label>
                    </div>
                    {{ Form::label('preorders_shown', trans('restaurant.show_in_preorders_menu'), ['class' => 'restaurant-group__bottom--label']) }}
                </div>
            </div>

            {!! Form::button($submitButtonText, [ 'type'=>'submit', 'class' => 'restaurant-group__bottom--submit']) !!}

        </div>
    </div>
</div>



@section('helpers')

    @component('components.modal', ['class' => 'restaurant-group__modal-languages js-restaurant-group__modal-languages'])

        <div class="restaurant-group__modal-languages--title">
            Languages
        </div>

        @if (count($menuLanguages))
            @foreach ($menuLanguages as $menuLanguage)
                @if($menuLanguage->language != $language)
                    <div class="restaurant-group__modal-languages-language">

                        <div class="restaurant-group__modal-languages-language--name">
                            {{ Form::label('names[' . $menuLanguage->language .']',  trans('restaurant.menu_name') . ' (' . trans('languages.'.$menuLanguage->language) . ')', ['class' => 'label']) }}
                            {{ Form::text('names[' . $menuLanguage->language .']', $group && $group->translatedName($menuLanguage->language) ? $group->translatedName($menuLanguage->language) : NULL , ['class' => 'input', 'form' => 'group-form']) }}
                        </div>

                        <div class="restaurant-group__modal-languages-language--description">
                            {{ Form::label('descriptions[' . $menuLanguage->language .']', trans('restaurant.description') . ' (' . trans('languages.'.$menuLanguage->language) . ')', ['class' => 'label']) }}
                            {{ Form::textarea('descriptions[' . $menuLanguage->language .']', $group && $group->translatedDescription($menuLanguage->language) ? $group->translatedDescription($menuLanguage->language) : NULL, ['class' => 'input', 'form' => 'group-form']) }}
                        </div>

                    </div>
                @endif
            @endforeach
        @endif

        {{ Form::button(trans('restaurant.create'), ['class' => 'restaurant-group__modal-languages--submit js-restaurant-group__modal-languages--submit']) }}


    @endcomponent
@stop


@section('script')
    <script>
        new Restaurant({
            newCourseActiveElement: '<div class="restaurant-group__course active js-restaurant-group__course"><div class="restaurant-group__course-header">' +
            '<div class="restaurant-group__course-header-link js-restaurant-group__course-header-link"><div class="restaurant-group__course-header--name">' +
            '{{ 'Course' }}' + '</div>' +
            '<div class="restaurant-group__course-header--number js-restaurant-group__course-header--number"></div><svg class="restaurant-group__course-header--icon">' +
            '<use xlink:href="#icon-arrow-down"></use></svg></div><svg class="restaurant-group__course-header--remove js-restaurant-group__course-header--remove"><use xlink:href="#icon-cross"></use></svg></div><div class="restaurant-group__course-content">' +
            '{{ Form::label('quantities[]', trans('restaurant.course_quantity'), ['class' => 'label']) }}' +
            '{{ Form::number('quantities[]', 1, ['class' => 'restaurant-group__course-content--quantity js-restaurant-group__course-content--quantity', 'min' => 1]) }}' +
            '<div class="js-restaurant-group__course-content-items restaurant-group__course-content-items"></div></div></div>',
            newItemElement:  '<div class="restaurant-group__course-content-item js-restaurant-group__course-content-item">' +
            '{{ Form::hidden('items[][]', null, ['class' => 'js-restaurant-group__course-content-item--hidden']) }}' +
            '<div class="restaurant-group__course-content-item--name js-restaurant-group__course-content-item--name"></div>' +
            '<svg class="restaurant-group__course-content-item--remove js-restaurant-group__course-content-item--remove"><use xlink:href="#icon-cross"></use></svg></div>',
            itemExistsMsg: '{!! trans('restaurant.item_already_added_to_course_error_msg') !!}'
        }).menuGroup();
    </script>
@stop

<div class="restaurant-menu__top">
    <div class="restaurant-menu__top-name">
        {!! Form::label('name', trans('general.name'), ['class' => 'label']) !!}
        {!! Form::text('name', NULL, ['class' => 'input', 'required', 'autofocus']) !!}
    </div>

    <div class="restaurant-menu__top-control">
        <div class="restaurant-menu__top-control-select">
            <div class="restaurant-menu__top-control-select--checkbox">
                {{ Form::checkbox('select-all', false, false, ['class' => 'js-restaurant-menu__top-control--checkbox', 'id' => 'select-all']) }}
                <label for="select-all"></label>
            </div>
            {{ Form::label('select-all','Select All Categories', ['class' => 'restaurant-menu__top-control-select--label']) }}
        </div>
        <!-- TODO search -->
        <div class="restaurant-menu__top-control-search">
            {{ Form::search('filter', null, ['class' => 'input']) }}
        </div>
    </div>
</div>

<div class="restaurant-menu__categories">
    <div class="restaurant-menu__categories-header">
        <div class="restaurant-menu__categories-header--name">
            Categories
        </div>

        <div class="restaurant-menu__categories-header--count js-restaurant-menu__categories-header--count"></div>
    </div>

    <div class="restaurant-menu__categories-content">

        @foreach ($categories as $category)
            @if (count($category->menu_items))
                @php
                    $checkbox = false;
                    foreach($category->menu_items as $item) {
                    $checkbox = $customMenu->id ? $customMenu->items->contains('id', $item->id) : $item->preorders_shown;
                        if($checkbox) {
                            break;
                        }
                    }
                @endphp
                <div class="restaurant-menu__category js-restaurant-menu__category {{ $checkbox ? 'selected' : '' }}">

                    <div class="restaurant-menu__category-top">
                        <div class="restaurant-menu__category-top-select js-restaurant-menu__category-top-select">
                            <div class="restaurant-menu__category-top--checkbox">


                                {{ Form::checkbox('categories[]', $category->id, $checkbox, ['class' => 'js-restaurant-menu__category-top--checkbox', 'id' => 'category_' . $category->id ]) }}
                                <label for="{{ 'category_' . $category->id }}"></label>
                            </div>
                        </div>

                        <div class="restaurant-menu__category-top-open js-restaurant-menu__category-top-open">
                            <div class="restaurant-menu__category-top--name">
                                {{ $category->translatedName($language) }}
                            </div>

                            <div class="restaurant-menu__category-top--count js-restaurant-menu__category-top--count"></div>

                            <svg class="restaurant-menu__category-top--icon">
                                <use xlink:href="#icon-arrow-down"></use>
                            </svg>
                        </div>

                    </div>

                    <div class="restaurant-menu__category-items js-restaurant-menu__category-items">
                        @foreach ($category->menu_items as $item)
                            <div class="restaurant-menu__category-item">
                                <div class="restaurant-menu__category-item-select">
                                    <div class="restaurant-menu__category-item--checkbox">
                                        {{ Form::checkbox('items[]',$item->id, $customMenu->id ? $customMenu->items->contains('id', $item->id) : $item->preorders_shown, ['class' => 'js-restaurant-menu__category-item--checkbox', 'id' => 'item_' . $item->id]) }}
                                        <label for="{{ 'item_' . $item->id }}"></label>
                                    </div>
                                </div>

                                <div class="restaurant-menu__category-item-desc">
                                    <div class="restaurant-menu__category-item-desc--name"
                                         title="{{ $item->translatedName($language) }}">
                                        {{ $item->translatedName($language) }}
                                    </div>

                                    <div class="restaurant-menu__category-item-desc--price">
                                        {{ \App\Misc::formatDecimal($item->price) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            @endif

        @endforeach


    </div>
</div>


@if(count($menuGroups))

    <div class="restaurant-menu__groups">
        <div class="restaurant-menu__groups-header">
            <div class="restaurant-menu__groups-header--name">
                {{ trans('restaurant.menus') }}
            </div>

            <div class="restaurant-menu__groups-header--count js-restaurant-menu__groups-header--count"></div>
        </div>

        <div class="restaurant-menu__groups-content">

            @foreach ($menuGroups as $menuGroup)
                <div class="restaurant-menu__group">
                    <div class="restaurant-menu__group--checkbox">
                        {{ Form::checkbox('menu_groups[]', $menuGroup->id, $customMenu->id ? $customMenu->menu_groups->contains('id', $menuGroup->id) : $menuGroup->preorders_shown, ['class' => 'js-restaurant-menu__group--checkbox', 'id' => 'group' . $menuGroup->id]) }}
                        <label for="{{ 'group' . $menuGroup->id }}"></label>
                    </div>

                    <label for="{{ 'group' . $menuGroup->id }}" class="restaurant-menu__group--label">
                        {{ $menuGroup->name }}
                        <!-- TODO correct number of items and translate -->
                        - {{ \App\Misc::formatDecimal($menuGroup->price) . \App\Misc::printCurrency() }}
                        {{ ' (' . $menuGroup->courses_count . ' ' . trans('restaurant.course_plural') . ', ' . $menuGroup->getItemsCount() . ' ' . trans('restaurant.item_plural') . ')'}}
                    </label>
                </div>
            @endforeach

        </div>
    </div>

@endif

<div class="restaurant-menu__bottom">
    {!! Form::button($submitButtonText, ['type' => 'submit', 'class' => 'restaurant-menu__bottom--submit']) !!}
</div>


@section('script')
    <script>
        new Restaurant().menu();
    </script>
@stop



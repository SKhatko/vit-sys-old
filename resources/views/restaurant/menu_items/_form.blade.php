<div class="restaurant-item__content">

    <div class="restaurant-item__content-data">

        <div class="restaurant-item__content-data-img">

            <div class="restaurant-item__content-data-img--centered">
                <div class="restaurant-item__content-data-img--wrapper">

                    <img src="{{ $item->image ? $item->image : asset('/images/no-photo.svg') }}"
                         class="restaurant-item__content-data-img--image js-restaurant-item__content-data-img--image">

                    <div id="croppic-image" class="restaurant-item__content-data-img--croppic"></div>

                    {{ Form::hidden('image', NULL, ['id' => 'croppic-input', 'class' => 'js-restaurant-item__content-data-img--name']) }}

                </div>

                @if($item->image)

                    <a href="javascript:" style="color:red;" id="remove-image-link"
                       class="restaurant-item__content-data-img--remove js-restaurant-item__content-data-img--remove">
                        <svg class="restaurant-item__content-data-img--remove-icon">
                            <use xlink:href="#icon-cross"></use>
                        </svg>
                    </a>

                @endif

                <a href="javascript:" id="cropic-upload-button" class="restaurant-item__content-data-img--upload"
                   title="{{ trans('general.upload_new_image') }}">
                    <svg class="restaurant-item__content-data-img--upload-icon">
                        <use xlink:href="#icon-upload"></use>
                    </svg>
                </a>

            </div>

        </div>

        {{ Form::label('category_id', trans('restaurant.menu_category'), ['class' => 'restaurant__label']) }}
        {{ \App\Misc::renderTreeSelect('category_id', $categoriesTree, $item->category ? [$item->category->id] : ($filterCategoryId ? [$filterCategoryId] : []), false, false, ['class' => 'select', 'required' => 'required', 'placeholder' => 'Select category']) }}

        {{ Form::label('price', trans('restaurant.price'), ['class' => 'restaurant__label']) }}
        {{ Form::text('price', NULL, ['class' => 'restaurant__input']) }}

        {{ Form::label('names[' . $language . ']', trans('general.name') . ' (' . trans('languages.'. $language ) . ')', ['class' => 'restaurant__label']) }}
        {{ Form::text('names[' . $language . ']', $item && $item->translatedName($language) ? $item->translatedName($language) : NULL, ['class' => 'restaurant__input']) }}

        {{ Form::label('descriptions[' . $language . ']', trans('general.description') . ' (' . trans('languages.'.$language ) . ')', ['class' => 'restaurant__label']) }}
        {{ Form::textarea('descriptions[' . $language . ']', $item && $item->translatedDescription($language) ? $item->translatedDescription($language) : NULL, ['class' => 'restaurant__input']) }}

    </div>

    <div class="restaurant-item__content-food">

        <div class="restaurant-item__content-food-header">

            <div class="restaurant-item__content-food-header--name">
                Food Allergies
                <span class="restaurant-item__content-food-header--count js-restaurant-item__content-food-header--count"></span>
            </div>

        </div>
        <div class="restaurant-item__content-food-content">

            @foreach ($foodAllergies as $allergy)
                <div class="restaurant-item__content-food-content-item">
                    <div class="restaurant-item__content-food-content--select">
                        <div class="restaurant-item__content-food-content--select-checkbox">
                            {{ Form::checkbox('allergies[]', $allergy->id, $item && in_array($allergy->id, $item->allergy_ids), ['id' => 'allergies_' . $allergy->id, 'class' => 'js-restaurant-item__content-food-content--select-checkbox']) }}
                            <label for="{{ 'allergies_' . $allergy->id }}"></label>
                        </div>
                    </div>

                    <div class="restaurant-item__content-food-content--name">
                        {{ Form::label('allergies_' . $allergy->id, trans('allergies.'.$allergy->name), ['class' => 'restaurant-item__content-food-content--name-label']) }}
                    </div>
                </div>

            @endforeach
        </div>

    </div>

    <div class="restaurant-item__content-drink">

        <div class="restaurant-item__content-drink-header">

            <div class="restaurant-item__content-drink-header--name">
                {{--                {{ trans('restaurant.food_contents') }}--}}
                Drink Allergies
                <span class="restaurant-item__content-drink-header--count js-restaurant-item__content-drink-header--count"></span>
            </div>

        </div>
        <div class="restaurant-item__content-drink-content" >

            @foreach ($drinkAllergies as $allergy)
                <div class="restaurant-item__content-drink-content-item">
                    <div class="restaurant-item__content-drink-content--select">
                        <div class="restaurant-item__content-drink-content--select-checkbox">
                            {{ Form::checkbox('allergies[]', $allergy->id, $item && in_array($allergy->id, $item->allergy_ids), ['id' => 'allergies_' . $allergy->id, 'class' => 'js-restaurant-item__content-drink-content--select-checkbox']) }}
                            <label for="{{ 'allergies_' . $allergy->id }}"></label>
                        </div>
                    </div>

                    <div class="restaurant-item__content-drink-content--name">
                        {{ Form::label('allergies_' . $allergy->id, trans('allergies.'.$allergy->name), ['class' => 'restaurant-item__content-drink-content--name-label']) }}
                    </div>
                </div>

            @endforeach
        </div>

    </div>

</div>

<div class="restaurant-item__footer">

    <div class="restaurant-item__footer-languages">
        <a href="javascript:" class="restaurant-item__footer-languages-link js-restaurant-item__footer-languages-link">
            More languages
            <svg class="restaurant-item__footer-languages-link--icon">
                <use xlink:href="#icon-arrow-down"></use>
            </svg>
        </a>
    </div>

    <div class="restaurant-item__footer-checkboxes">

        <div class="restaurant-item__footer-checkbox">
            {{ Form::checkbox('online_shown', true, $item && $item->online_shown, ['id' => 'online_shown']) }}
            <label for="online_shown"></label>
        </div>

        {{ Form::label('online_shown',  trans('restaurant.show_in_online_menu'), ['class' => 'restaurant-item__footer-checkbox--label']) }}

        <div class="restaurant-item__footer-checkbox">
            {{ Form::checkbox('preorders_shown', true, $item && $item->preorders_shown, ['id' => 'preorders_shown']) }}
            <label for="preorders_shown"></label>
        </div>

        {{ Form::label('preorders_shown',  trans('restaurant.show_in_preorders_menu'), ['class' => 'restaurant-item__footer-checkbox--label']) }}

    </div>

</div>

<div class="restaurant-item-submit">
    {{ Form::button($submitButtonText, ['type'=>'submit', 'class' => 'restaurant-item-submit__button']) }}
</div>

@section('helpers')
    <!----- edit ITEM MODAL ------>
    @component('components.modal', ['class' => 'restaurant-item__modal-languages js-restaurant-item__modal-languages'])

        <div class="restaurant-item__modal-languages--title">
            Add languages
        </div>


        @if (count($menuLanguages))
            @foreach ($menuLanguages as $menuLanguage)
                @if($menuLanguage->language != $language)
                    <div class="restaurant-item__modal-languages-content">

                        <div class="restaurant-item__modal-languages-content--name">
                            {{ Form::label('names[' . $menuLanguage->language . ']', trans('general.name') . ' (' . trans('languages.'.$menuLanguage->language) . ')', ['class' => 'restaurant__label'])  }}
                            {{ Form::text('names[' . $menuLanguage->language . ']', $item && $item->translatedName($menuLanguage->language) ? $item->translatedName($menuLanguage->language) : NULL, ['class' => 'restaurant__input', 'form' => 'item-data-form']) }}
                        </div>

                        <div class="restaurant-item__modal-languages-content--description">
                            {{ Form::label('descriptions[' . $menuLanguage->language . ']', trans('general.description') . ' (' . trans('languages.'.$menuLanguage->language) . ')', ['class' => 'restaurant__label']) }}
                            {{ Form::textarea('descriptions[' . $menuLanguage->language . ']', $item && $item->translatedDescription($menuLanguage->language) ? $item->translatedDescription($menuLanguage->language) : NULL, ['class' => 'restaurant__input', 'form' => 'item-data-form']) }}
                        </div>
                    </div>

                @endif
            @endforeach
        @endif

        {{ Form::button('Save', ['class' => 'restaurant-item__modal-languages--submit js-restaurant-item__modal-languages--submit']) }}

    @endcomponent

@stop

@section('script')
    <script>
        new Restaurant({
            csrf_token: "{{ csrf_token() }}",
            outputUrlId: 'croppic-input',
            noPhotoPath: '{{ asset('/images/no-photo.svg') }}'
        }).menuItem();
    </script>
@stop

<div class="restaurant-category__content">

    <div class="restaurant-category__content-data">

        <div class="restaurant-category__content-data-img">

            <div class="restaurant-category__content-data-img--centered">
                <div class="restaurant-category__content-data-img--wrapper">

                    <img src="{{ $category->image ? $category->image : asset('/images/no-photo.svg') }}"
                         class="restaurant-category__content-data-img--image js-restaurant-category__content-data-img--image">

                    <div id="croppic-image" class="restaurant-category__content-data-img--croppic"></div>

                    {{ Form::hidden('image', NULL, ['id' => 'croppic-input', 'class' => 'js-restaurant-category__content-data-img--name']) }}

                </div>

                @if($category->image)

                    <a href="javascript:" style="color:red;" id="remove-image-link"
                       class="restaurant-category__content-data-img--remove js-restaurant-category__content-data-img--remove">
                        <svg class="restaurant-category__content-data-img--remove-icon">
                            <use xlink:href="#icon-cross"></use>
                        </svg>
                    </a>

                @endif

                <a href="javascript:" id="cropic-upload-button" class="restaurant-category__content-data-img--upload"
                   title="{{ trans('general.upload_new_image') }}">
                    <svg class="restaurant-category__content-data-img--upload-icon">
                        <use xlink:href="#icon-upload"></use>
                    </svg>
                </a>

            </div>

        </div>

        <div class="restaurant-category__content-data-columns">
            <div class="restaurant-category__content-data-name">
                {{ Form::label('names[' . $language . ']', trans('restaurant.category_name') . ' (' . trans('languages.'. $language ) . ')', ['class' => 'restaurant__label']) }}
                {{ Form::text('names[' . $language . ']', $category && $category->translatedName($language) ? $category->translatedName($language) : NULL, ['class' => 'restaurant__input', 'required', 'autofocus']) }}
            </div>

            <div class="restaurant-category__content-data-category">
                {{ Form::label('parent_id', trans('restaurant.parent_category'), ['class' => 'restaurant__label']) }}
                {{ \App\Misc::renderTreeSelect('parent_id', $categoriesTree, [$category->parent_id], false, true, ['class' => 'select', 'placeholder' => 'Select Category']) }}
            </div>
        </div>

    </div>

    <div class="restaurant-category__content-languages">
        @if (count($menuLanguages))
            <div class="restaurant-category__content-languages--title">
                Languages
            </div>

        <div class="restaurant-category__content-languages-wrapper">
            @foreach ($menuLanguages as $menuLanguage)
                @if($menuLanguage->language != $language)
                    <div class="restaurant-category__content-languages-item">
                        {{ Form::label('names[' . $menuLanguage->language . ']', trans('general.name') . ' (' . trans('languages.'.$menuLanguage->language) . ')', ['class' => 'restaurant__label'])  }}
                        {{ Form::text('names[' . $menuLanguage->language . ']', $category && $category->translatedName($menuLanguage->language) ? $category->translatedName($menuLanguage->language) : NULL, ['class' => 'restaurant__input']) }}
                    </div>
                @endif
            @endforeach
        </div>

        @endif
    </div>

</div>

<div class="restaurant-category-submit">
    {{ Form::button($submitButtonText, ['type'=>'submit','class' => 'restaurant-category-submit__button']) }}
</div>

@section('script')
    <script>

        new Restaurant({
            csrf_token: "{{ csrf_token() }}",
            outputUrlId: 'croppic-input',
            noPhotoPath: '{{ asset('/images/no-photo.svg') }}'
        }).menuCategory();

    </script>
@stop

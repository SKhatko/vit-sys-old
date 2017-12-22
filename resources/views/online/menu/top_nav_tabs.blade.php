@extends('online.menu.master')

@section('style')
    <style>
        html, body {
            width: 100%;
            min-height: 100%;
            padding: 0;
            margin: 0;
            font-family: {!! \App\Misc::cleanFontName($settings['content_font']) !!};
        }

        body {
            height: auto;
            color: {{ $settings['content_font_color'] }};
            @if ($settings['background_type'] == 'color' && $settings['background_color'])
                 background-color: {{ $settings['background_color'] }};
            @endif
             font-size: {{ $settings['content_font_size'] }}px;
        }

        .clear {
            clear: both;
        }

        ul {
            padding: 0;
            display: block;
            width: 100%;
        }

        #background-img {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: -2;
            display: none;
            transition: background-image .5s ease-in-out;
        }

        #background-img img {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            -webkit-transition: opacity .5s ease-in-out;
            -moz-transition: opacity .5s ease-in-out;
            -o-transition: opacity .5s ease-in-out;
            transition: opacity .5s ease-in-out;
            display: none;
        }

        #content-wrapper {
            width: 90%;
            max-width: 980px;
            margin: 30px auto;
            background: {{ \App\Misc::getColorAndOpacityCssValue($settings['content_background_color'], $settings['content_background_opacity']) }};
            padding: 20px;
            box-shadow: 0 0 15px #333;
            height: auto;
            min-height: 400px;
        }

        .category-holder {
            display: none;
        }

        #nav-holder {
            padding: 0 35px;

            font-family: {!! \App\Misc::cleanFontName($settings['navigation_font']) !!};
            font-size: {{ $settings['navigation_font_size'] }}px;

            font-weight: {{ $settings['navigation_bold'] ? 'bold' : 'normal' }};
            font-style: {{ $settings['navigation_italic'] ? 'italic' : 'normal' }};

        }

        .categories-nav {
            padding: 0;
            list-display-type: none;
            z-index: 10;
        }

        .categories-nav li {
            list-style: none;
            line-height: 26px;
            display: inline-block;
            margin: 5px 2px;
        }

        .categories-nav li a {
            display: block;
            padding: 5px 10px;
            color: {{ $settings['navigation_font_color'] }};
            text-decoration: none;
            border: 1px solid {{ $settings['navigation_font_color'] }};
        }

        .categories-nav li a:hover, .categories-nav li a.active, .categories-nav li a:active {
            text-decoration: none;
            background: {{ $settings['navigation_font_color'] }};
            color: {{ $settings['content_background_color'] }};
        }

        h1 {
            font-family: {!! \App\Misc::cleanFontName($settings['main_category_font']) !!};
            font-size: {{ $settings['main_category_font_size'] }}px;
            color: {{ $settings['main_category_font_color'] }};
            text-align: {!! $settings['main_category_alignment'] !!};
            margin-bottom: 30px;
            margin-left: 35px;
            margin-right: 35px;

            font-weight: {{ $settings['main_category_bold'] ? 'bold' : 'normal' }};
            font-style: {{ $settings['main_category_italic'] ? 'italic' : 'normal' }};
            text-decoration: {{ $settings['main_category_underlined'] ? 'underline' : 'none' }};
        }

        h2 {
            font-family: {!! \App\Misc::cleanFontName($settings['sub_category_font']) !!};
            font-size: {{ $settings['sub_category_font_size'] }}px;
            color: {{ $settings['sub_category_font_color'] }};
            margin-bottom: 30px;
            margin-left: 35px;
            margin-right: 35px;
            text-align: {{ $settings['sub_category_alignment'] }};

            font-weight: {{ $settings['sub_category_bold'] ? 'bold' : 'normal' }};
            font-style: {{ $settings['sub_category_italic'] ? 'italic' : 'normal' }};
            text-decoration: {{ $settings['sub_category_underlined'] ? 'underline' : 'none' }};
        }

        .menu-item-holder {
            margin-bottom: 30px;
            min-height: 80px;
            padding-left: 35px;
            padding-right: 35px;
        }

        .menu-item-prepended {
            display: block;
            border-top: 1px dashed #666;
            height: 5px;
        }

        .menu-item-holder > img {
            float: left;
            width: {{ $settings['item_image_width'] }}px;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .menu-item-content {
            /*display:inline-block;*/
            overflow: hidden;
        }

        .menu-item-name {
            display: inline-block;
            font-family: {!! \App\Misc::cleanFontName($settings['item_name_font']) !!};
            color: {{ $settings['item_name_font_color'] }};
            font-size: {{ $settings['item_name_font_size'] }}px;

            font-weight: {{ $settings['item_name_bold'] ? 'bold' : 'normal' }};
            font-style: {{ $settings['item_name_italic'] ? 'italic' : 'normal' }};
            text-decoration: {{ $settings['item_name_underlined'] ? 'underline' : 'none' }};

            padding-right: 50px; /* To prevent overlap with price */

        }

        .menu-item-description {
            display: block;
            font-family: {!! \App\Misc::cleanFontName($settings['item_description_font']) !!};
            color: {{ $settings['item_description_font_color'] }};
            font-size: {{ $settings['item_description_font_size'] }}px;
            white-space: pre-wrap;

            font-weight: {{ $settings['item_description_bold'] ? 'bold' : 'normal' }};
            font-style: {{ $settings['item_description_italic'] ? 'italic' : 'normal' }};
            text-decoration: {{ $settings['item_description_underlined'] ? 'underline' : 'none' }};
        }

        .menu-item-price {
            position: absolute;
            top: 0;
            right: 35px;
            font-family: {!! \App\Misc::cleanFontName($settings['item_price_font']) !!};
            color: {{ $settings['item_price_font_color'] }};
            font-size: {{ $settings['item_price_font_size'] }}px;

            font-weight: {{ $settings['item_price_bold'] ? 'bold' : 'normal' }};
            font-style: {{ $settings['item_price_italic'] ? 'italic' : 'normal' }};
            text-decoration: {{ $settings['item_price_underlined'] ? 'underline' : 'none' }};
        }

        .menu-item-name-prefix {
            font-size: {{ $settings['item_name_font_size'] }}px;
            color: {{ $settings['sub_sub_category_font_color'] }};

            font-weight: {{ $settings['sub_sub_category_bold'] ? 'bold' : 'normal' }};
            font-style: {{ $settings['sub_sub_category_italic'] ? 'italic' : 'normal' }};
            text-decoration: {{ $settings['sub_sub_category_underlined'] ? 'underline' : 'none' }};
        }

        .menu-prefix {
            display: block;
            width: 50%;
            margin: 0 auto;
            height: 4px;
            border-top: 2px dotted #000;
        }

        .menu-description {
            text-align: center;
            margin-bottom: 35px;

            font-size: {{ $settings['menu_description_font_size'] }}px;
            color: {{ $settings['menu_description_font_color'] }};

            font-weight: {{ $settings['menu_description_bold'] ? 'bold' : 'normal' }};
            font-style: {{ $settings['menu_description_italic'] ? 'italic' : 'normal' }};
            text-decoration: {{ $settings['menu_description_underlined'] ? 'underline' : 'none' }};

            white-space: pre-wrap;
        }

        #uparrow {
            background: rgba(255, 255, 255, 0.9);
            cursor: pointer;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            position: fixed;
            bottom: 40px;
            right: 20px;
            box-shadow: 0 0 10px 1px #333;
            /* border-radius: 12px; */
            display: none;
            z-index: 999;
            text-align: center;
            padding-top: 12px;
            overflow: hidden;
        }

        .course-quantity {
            font-size: 18px;
            font-weight: bold;
            color: black;
        }

        #uparrow::after {
            content: "\25B2";
            font-size: 20px;
            line-height: 24px;
        }

        #mobile-nav-holder {
            display: none;
        }

        .allergy-icons {
            margin-top: 5px;
        }

        .allergy-icon {
            margin-right: 7px;
        }

        .allergy-icon img {
            width: 30px;
        }

        .highlighted {
            border: 2px dashed red;
        }

        #vitisch-logo-holder {

            position: fixed;
            bottom: 15px;
            left: 15px;

        }

        #vitisch-logo-holder img {
            height: 40px;
        }

        @if (!$settings['display_menu_items_price'])
		.menu-content .menu-item-price {
            display: none;
        }

        @endif

        @media (max-width: 767px) {

            #nav-holder {
                display: none;
                padding: 0 15px;
            }

            #mobile-nav-holder {
                display: block;
            }

            .menu-item-holder {
                padding-left: 15px;
                padding-right: 15px;
            }

            .menu-item-price {
                right: 15px;
            }

            h2 {
                margin-left: 15px;
            }

            #vitisch-logo-holder {
                display: none;
            }
        }

        <?php
	/******** CSS to cover edit mode (temp) settings ********/
	?>
	@if (isset($temp))
		@if (!$settings['display_prices'])
			.menu-item-price {
            display: none;
        }

        @endif
		
		@if (!$settings['display_images'])
			.menu-item-holder > img {
            display: none;
        }

        @endif
		
		@if (!$settings['display_allergies'])
			.allergy-icons {
            display: none;
        }

        @endif
		
		@if (!$settings['scroll_top'])
			#uparrow {
            width: 0px;
            height: 0px;
        }

        @endif
		
		.menu-item-description.empty {
            display: none;
        }
        @endif


        {!! $settings['custom_css'] !!}
    </style>
@stop


@section('body')
    <body>
    @include('online.menu._language_form')

    <div id="extra-style"></div>

    <div id="background-img">

        @if (isset($temp) || $settings['background_type'] == 'inherit')

            @foreach ($categories as $category)
                @if (!$category->parent_id)
                    @if ($category->image)
                        <img src="{{ url('/') }}/{{ $category->image }}"
                             alt="{{ $category->translatedName($menuLanguage) }}"
                             data-category-id="{{ $category->id }}"/>
                    @else
                        <img src="{{ url('/') }}/{{ $settings['background_image'] }}"
                             alt="{{ $category->translatedName($menuLanguage) }}"
                             data-category-id="{{ $category->id }}"/>
                    @endif

                @endif
            @endforeach

            <img src="{{ url('/') }}/{{ $settings['menus_background'] }}" alt="menus" data-category-id="menus"/>

        @endif

    </div>

    @if (isset($temp) || $settings['scroll_top'])
        <div id="uparrow"></div>
    @endif

    <script>
        function setBackgroundImage(url) {

            var screenW = screen.width;
            var slashPos = url.lastIndexOf("/");

            if (screenW <= 728) {
                url = url.substring(0, slashPos) + "/small" + url.substring(slashPos);
            }
            else if (screenW <= 1400) {
                url = url.substring(0, slashPos) + "/medium" + url.substring(slashPos);
            }
            else {
                url = url.substring(0, slashPos) + "/large" + url.substring(slashPos);
            }

            var backgroundImgDom = document.getElementById('background-img');
            backgroundImgDom.style.display = 'block';
            backgroundImgDom.style.background = 'url("' + url + '") no-repeat center center';
            backgroundImgDom.style.backgroundSize = 'cover';

        }
    </script>

    @if ($settings['background_type'] == 'image' && $settings['background_image'])

        <script>
            var url = "{{ url('/') }}/{{ $settings['background_image'] }}";
            setBackgroundImage(url);
        </script>
    @endif

    <div id="content-wrapper" class="content-section">
        <div id="nav-holder" class="navigation-section">
            <ul class="categories-nav">
                @foreach ($categoriesTree as $id => $item)
                    @if ($item['object']->translatedName($menuLanguage))
                        <li><a href="javascript:;" id="category-link-{{ $id }}" onclick="goToCategory({{ $id }});"
                               data-category-id="{{ $id }}">{{ $item['object']->translatedName($menuLanguage) }}</a>
                        </li>
                    @endif
                @endforeach

                @if (count($menuGroups))
                    @if (\App\MenuSingleton::getInstance()->menu_title($menuLanguage))
                        <li><a href="javascript:;" id="category-link-menus" onclick="goToCategory('menus')"
                               data-category-id="menus">{{ \App\MenuSingleton::getInstance()->menu_title($menuLanguage) }}</a>
                        </li>
                    @endif
                @endif
            </ul>
        </div>

        <div id="mobile-nav-holder" class="navigation-section">
            <div style="padding:0 15px;">
                <select id="nav-sel" class="form-control">
                    @foreach ($categoriesTree as $id => $item)
                        @if ($item['object']->translatedName($menuLanguage))
                            <option value="{{ $id }}">{{ $item['object']->translatedName($menuLanguage) }}</option>
                        @endif
                    @endforeach

                    @if (count($menuGroups))
                        @if (\App\MenuSingleton::getInstance()->menu_title($menuLanguage))
                            <option value="menus">{{ \App\MenuSingleton::getInstance()->menu_title($menuLanguage) }}</option>
                        @endif
                    @endif
                </select>
            </div>
        </div>

        <div style="padding:0 15px;">
            <hr>
        </div>

        <div class="categories-holder">
            <?php
            $config = [
                    'print_items' => true,
                    'items_per_row' => 2,
                    'item_holder_classes' => ['col-md-6', 'col-sm-6', 'col-xs-12'],
                    'language' => $menuLanguage,
            ];
            \App\Misc::renderOnlineMenuA($categoriesTree, $settings, $config, isset($temp));
            ?>
        </div>
    </div>

    <div id="vitisch-logo-holder">
        <img src="{{ asset('/img/vitisch-logo.png') }}"/>
    </div>

    <script src="{{ asset('js/language-switcher.js?v=1') }}"></script>
    <script>
                @if ($settings['background_type'] == 'inherit')
        var inherit = true;
                @else
        var inherit = false;
        @endif

        function goToCategory(id) {

            $(".category-holder").hide();
            $("#nav-sel").val(id);
            $(".category-holder[id='cat-" + id + "']").fadeIn(500);
            $(".category-holder[id='cat-" + id + "'] .category-holder").fadeIn(500);
            $(".categories-nav li a").removeClass('active');
            $("#category-link-" + id).addClass('active');

            if (inherit) {
                var correspondingImg = $("#background-img img[data-category-id='" + id + "']").attr('src');
                setBackgroundImage(correspondingImg);
            }
        }

        function activateSlider() {

            $("#background-img").show();
            var activeId = $(".categories-nav li a.active").attr('data-category-id');
            if (activeId) {
                var correspondingImg = $("#background-img img[data-category-id='" + activeId + "']").attr('src');
                setBackgroundImage(correspondingImg);
            }
            inherit = true;

        }

        function deactivateSlider() {
            inherit = false;
        }

        function setMenusFirst() {
            var $menusLink = $("#category-link-menus").parent();
            $(".categories-nav").prepend($menusLink);

            var $menusOption = $("option[value='menus']");
            $("#nav-sel").prepend($menusOption);

            $menusLink.show();
            $menusOption.show();
        }

        function setMenusLast() {
            var $menusLink = $("#category-link-menus").parent();
            $(".categories-nav").append($menusLink);

            var $menusOption = $("option[value='menus']");
            $("#nav-sel").append($menusOption);

            $menusLink.show();
            $menusOption.show();
        }

        function removeMenus() {
            $("#category-link-menus").parent().hide();
            $("option[value='menus']").hide();
        }

        $(document).ready(function () {

            @if ($settings['background_type'] == 'inherit')
                activateSlider();
            @endif

            @if (intval($settings['display_menus']) !== 0)
                @if ($settings['display_menus'] == 1)
                    setMenusFirst();
            @endif
        @elseif (isset($temp))
            removeMenus();
            @endif

            $(".categories-nav li a").first().click();

            $(".menu-prefix").first().remove();

            $("#nav-sel").change(function () {
                var id = $(this).val();
                goToCategory(id);
            });

            @if (isset($temp) || $settings['scroll_top'])

                $(window).scroll(function () {
                if ($(this).scrollTop() > 50) {
                    $('#uparrow').fadeIn();
                } else {
                    $('#uparrow').fadeOut();
                }
            });

            $('#uparrow').click(function () {
                $('body,html').animate({
                    scrollTop: 0
                }, 500);
            });
            @endif
        });

        @if (isset($temp))

        /** Editor Api Functions **/

        //Append section names
        $(".category-holder h1").attr('data-section-name', 'main-category-title');
        $(".category-holder h2").attr('data-section-name', 'sub-category-title');
        $("#nav-holder").attr('data-section-name', 'navigation');
        $(".menu-item-name-prefix").attr('data-section-name', 'sub-sub-category-title');
        $(".menu-item-name").attr('data-section-name', 'item-name');
        $(".menu-item-description").attr('data-section-name', 'item-description');
        $(".menu-item-price").attr('data-section-name', 'item-price');
        $(".menu-item-holder > img").attr('data-section-name', 'item-image');
        $("#content-wrapper").attr('data-section-name', 'content');
        $("#background-img").attr('data-section-name', 'background');
        $(".allergy-icons").attr('data-section-name', 'allergies');
        $(".menu-description").attr('data-section-name', 'menu-description');

        function _setBackground(type, value) {
            if (type == 'color') {
                _setBackgroundColor(value);
                deactivateSlider();
            }
            else if (type == 'image') {
                _setBackgroundImage(value);
                deactivateSlider();
            }
            else if (type == 'inherit') {
                activateSlider();
            }
        }

        function _setBackgroundImage(value) {
            $("body").css('background-color', 'white');
            setBackgroundImage(value); //function defined in theme (not api function)
        }

        function _setBackgroundColor(value) {
            $("body").css('background-color', value);
            $("[data-section-name='background']").css('display', 'none');
        }

        function _formatElement(section, option, flag) {

            var $myObjects = $("[data-section-name='" + section + "']");

            var cssValue = '';
            if (option == 'bold') {
                cssValue = (flag) ? 'bold' : 'normal';
                $myObjects.css('font-weight', cssValue);
            }
            else if (option == 'italic') {
                cssValue = (flag) ? 'italic' : 'normal';
                $myObjects.css('font-style', cssValue);
            }
            else if (option == 'underlined') {
                cssValue = (flag) ? 'underline' : 'none';
                $myObjects.css('text-decoration', cssValue);
            }
            else {
                console.log(option);
            }
        }

        function _alignElement(section, value) {
            var $myObjects = $("[data-section-name='" + section + "']");
            if (value == 'underlined') {
                value = 'underline';
            }
            $myObjects.css('text-align', value);
        }

        function _setFontSize(section, pixels) {
            var $myObjects = $("[data-section-name='" + section + "']");
            $myObjects.css('font-size', pixels + 'px');

            if (section == 'item-name') {
                $(".menu-item-name-prefix").css('font-size', pixels + 'px');
            }
        }

        function _setFontColor(section, color) {
            if (section == 'navigation') {
                var newStyle = "<style>";
                newStyle += '.categories-nav li a { color: ' + color + '; border:1px solid ' + color + '; }';
                newStyle += '.categories-nav li a:hover, .categories-nav li a.active, .categories-nav li a:active { background-color: ' + color + '; }';
                newStyle += "</style>";
                $("#extra-style").html(newStyle);
            }
            else {
                var $myObjects = $("[data-section-name='" + section + "']");
                $myObjects.css('color', color);
            }
        }

        function _setContentBackground(color, opacity) {
            var value = hexToRgba(color, opacity);
            $("[data-section-name='content']").css('background', value);
        }

        function _setNavigationBackground(color, opacity) {
            var value = hexToRgba(color, opacity);
            $("[data-section-name='navigation']").css('background', value);
        }

        function _setDisplayPrices(flag) {
            if (flag) {
                $("[data-section-name='item-price']").show();
            }
            else {
                $("[data-section-name='item-price']").hide();
            }
        }

        function _setDisplayImages(flag) {
            if (flag) {
                $("[data-section-name='item-image']").show();
            }
            else {
                $("[data-section-name='item-image']").hide();
            }
        }

        function _setDisplayAllergies(flag) {
            if (flag) {
                $("[data-section-name='allergies']").show();
            }
            else {
                $("[data-section-name='allergies']").hide();
            }
        }

        function _setDisplayMenus(flag) {
            if (flag == 1) {
                setMenusFirst();
            }
            else if (flag == -1) {
                setMenusLast();
            }
            else {
                removeMenus();
            }
        }

        function _setDisplayMenuItemsPrice(flag) {
            if (flag) {
                $(".menu-content .menu-item-price").show();
            }
            else {
                $(".menu-content .menu-item-price").hide();
            }
        }

        function _setScrollTop(flag) {
            console.log('setting scroll top: ' + flag);
            if (flag) {
                showScrollTop();
            }
            else {
                hideScrollTop();
            }
        }

        function _setItemImageWidth(width) {
            $("img[data-section-name='item-image']").css('width', width + 'px');
        }

        function _highlightSection(section) {
            $section = $("[data-section-name='" + section + "']");
            if ($section) {
                $section.addClass('highlighted');
            }
        }

        function _unhighlightSections() {
            $(".highlighted").removeClass('highlighted');
        }

        function _setFont(section, fontName) {
            fontName = cleanFontName(fontName);
            console.log('setting-font: ' + fontName + ', section: ' + section);
            $("[data-section-name='" + section + "']").css('font-family', fontName);
        }

        function _importFont(fontName) {
            var fontUrl = 'https://fonts.googleapis.com/css?family=' + fontName + ':400,700';
            var htmlLink = '<link href="' + fontUrl + '" data-font-name="' + fontName + '" rel="stylesheet">';
            $("head").append(htmlLink);
        }

        function _removeFont(fontName) {
            console.log('removing font: ' + fontName);
            $("link[data-font-name='" + fontName + "']").remove();
        }

        /** Theme helper functions (Edit mode) **/
        function cleanFontName(font) {
            return font.split('+').join(' ');
        }

        function hexToRgba(color, opacity) {

            var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(color);
            var opacity = opacity / 100;
            var value = "";
            if (result) {
                var r = parseInt(result[1], 16),
                        g = parseInt(result[2], 16),
                        b = parseInt(result[3], 16);

                value = "rgba(" + r + "," + g + "," + b + ", " + opacity + ")";
            }

            console.log(value);
            return value;
        }

        function hideScrollTop() {
            $("#uparrow").css('width', '0px');
            $("#uparrow").css('height', '0px');
        }

        function showScrollTop() {
            $("#uparrow").css('width', '50px');
            $("#uparrow").css('height', '50px');
        }

        @endif
    </script>
    </body>
@stop
	
	
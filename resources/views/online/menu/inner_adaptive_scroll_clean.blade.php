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
            background-color: {{ $settings['content_background_color'] }};
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

        #content-wrapper {
            width: 100%;
            margin: 0;
            padding: 20px 0 0;
            height: 100%; /* Changed to variable */
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

        .course-quantity {
            font-size: 18px;
            font-weight: bold;
            color: black;
        }

        #mobile-nav-holder {
            display: none;
        }

        .categories-holder {
            height: 100%;
            overflow-y: auto;
        }

        #mobile-nav-holder {
            display: none;
        }

        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
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

        @if (!$settings['display_menu_items_price'])
		.menu-content .menu-item-price {
            display: none;
        }

        @endif

        @media (max-width: 991px) {

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

            @if (count($menuLanguages))
			#language-select {
                right: 16px;
            }

            #mobile-nav-holder {
                margin-top: 23px;
            }

        @endif

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
    <body class="content-section">

    @include('online.menu._language_form')

    <div id="extra-style">

    </div>

    <div id="content-wrapper">

        <div id="nav-holder" class="navigation-section">
            <ul class="categories-nav">
                @foreach ($categoriesTree as $id => $item)
                    <li><a href="javascript:;" id="category-link-{{ $id }}"
                           onclick="goToCategory({{ $id }});">{{ $item['object']->translatedName($menuLanguage) }}</a>
                    </li>
                @endforeach

                @if (count($menuGroups))
                    <li><a href="javascript:;" id="category-link-menus" onclick="goToCategory('menus')"
                           data-category-id="menus">{{ \App\MenuSingleton::getInstance()->menu_title($menuLanguage) }}</a>
                    </li>
                @endif
            </ul>
        </div>

        <div id="mobile-nav-holder" class="navigation-section">
            <div style="padding:0 15px;">
                <select id="nav-sel" class="form-control">
                    @foreach ($categoriesTree as $id => $item)
                        <option value="{{ $id }}">{{ $item['object']->translatedName($menuLanguage) }}</option>
                    @endforeach

                    @if (count($menuGroups))
                        <option value="menus">{{ \App\MenuSingleton::getInstance()->menu_title($menuLanguage) }}</option>
                    @endif
                </select>
            </div>
        </div>


        <div style="padding:0 15px;">
            <hr></hr>
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

    <script src="{{ asset('js/language-switcher.js') }}"></script>
    <script>

        function goToCategory(id) {

            $(".category-holder").hide();
            $("#nav-sel").val(id);
            $(".category-holder[id='cat-" + id + "']").fadeIn();
            $(".category-holder[id='cat-" + id + "'] .category-holder").fadeIn();
            $(".categories-nav li a").removeClass('active');
            $("#category-link-" + id).addClass('active');

        }

        function fixHeights() {

            var windowHeight = window.innerHeight;
            var scrollSectionTop = $(".categories-holder").offset().top;
            $(".categories-holder").css("height", windowHeight - scrollSectionTop);
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

            @if (intval($settings['display_menus']) !== 0)
                @if ($settings['display_menus'] == 1)
                    setMenusFirst();
            @endif
        @elseif (isset($temp))
            removeMenus();
            @endif

            $(".categories-nav li a").first().click();

            $("#nav-sel").change(function () {
                var id = $(this).val();
                goToCategory(id);
            });

            $(window).resize(function () {
                fixHeights();
            });

            fixHeights();

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
        $(".allergy-icons").attr('data-section-name', 'allergies');
        $(".menu-description").attr('data-section-name', 'menu-description');

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

        @endif
    </script>
    </body>
@stop
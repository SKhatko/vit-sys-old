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
            background: {{ \App\Misc::getColorAndOpacityCssValue($settings['content_background_color'], $settings['content_background_opacity']) }};
        }

        .clear {
            clear: both;
        }

        ul {
            padding: 0;
            margin: 0;
            display: block;
            width: 100%;
        }

        #nav-holder {
            background: {{ \App\Misc::getColorAndOpacityCssValue($settings['navigation_background_color'], $settings['navigation_background_opacity']) }};
            padding: 0;
            box-shadow: 0 0 1px #aaa;

            font-family: {!! \App\Misc::cleanFontName($settings['navigation_font']) !!};
            font-size: {{ $settings['navigation_font_size'] }}px;

            font-weight: {{ $settings['navigation_bold'] ? 'bold' : 'normal' }};
            font-style: {{ $settings['navigation_italic'] ? 'italic' : 'normal' }};
        }

        .categories-holder > ul > .pre-category, .menu-holder > ul > .pre-category {
            background-color: white;
            width: 100%;
            height: 400px;
        }

        .categories-nav {
            list-display-type: none;
            z-index: 10;
            max-width: 1020px;
            margin: 0 auto;
            padding: 0 35px;
        }

        .categories-nav:after {
            clear: both;
        }

        .categories-nav li {
            list-style: none;
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
            color: {{ $settings['navigation_background_color'] }};
        }

        .category-holder {
            max-width: 1020px;
            margin: 0 auto;
            padding-top: 30px;
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
            width: 60%;
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

        #uparrow {
            background: rgba(255, 255, 255, 0.9);
            cursor: pointer;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            position: fixed;
            bottom: 15px;
            right: 15px;
            box-shadow: 0 0 10px 1px #333;
            /* border-radius: 12px; */
            display: none;
            z-index: 999;
            text-align: center;
            padding-top: 12px;
            overflow: hidden;
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

        .parallax-bg {
            background-attachment: fixed;
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

        @media (max-width: 991px) {

            #nav-holder {
                display: none;
            }

            #mobile-nav-holder {
                display: block;
                padding: 12px 0;
                background: #fff;
                box-shadow: 0 0 1px #aaa;
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

        @media (max-width: 1024px) {

            .parallax-bg {
                background-attachment: scroll;
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

    @if (isset($temp))
        <div id="extra-style"></div>
    @endif

    @if (isset($temp) || $settings['scroll_top'])
        <div id="uparrow"></div>
    @endif

    <div id="nav-holder" class="navigation-section">
        <ul class="categories-nav">
            @foreach ($categoriesTree as $id => $item)
                @if ($item['object']->translatedName($menuLanguage))
                    <li><a href="javascript:;" id="category-link-{{ $id }}"
                           onclick="goToCategory({{ $id }});">{{ $item['object']->translatedName($menuLanguage) }}</a>
                    </li>
                @endif
            @endforeach

            @if (count($menuGroups))
                @if (\App\MenuSingleton::getInstance()->menu_title($menuLanguage))
                    <li><a href="javascript:;" id="category-link-menus"
                           onclick="goToCategory('menus')">{{ \App\MenuSingleton::getInstance()->menu_title($menuLanguage) }}</a>
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

    <div class="categories-holder">
        <?php
        $config = [
                'print_items' => true,
                'items_per_row' => 2,
                'item_holder_classes' => ['col-md-6', 'col-sm-6', 'col-xs-12'],
                'category_holder_opener' => '<div class="pre-category" id="pre-cat-%id%" data-category-id="%id%"></div><div class="category-holder" id="cat-%id%" data-category-id="%id%">',
                'language' => $menuLanguage,
        ];
        \App\Misc::renderOnlineMenuA($categoriesTree, $settings, $config, isset($temp));
        ?>
    </div>

    <div id="vitisch-logo-holder">
        <img src="{{ asset('/img/vitisch-logo.png') }}"/>
    </div>

    <script src="{{ asset('js/language-switcher.js') }}"></script>
    <script>
        function getResizedImageUrl(url) {

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

            return url;
        }

        function loadImageBackground(elementId, imageUrl, parallax) {

            var url = getResizedImageUrl(imageUrl);

            var dom = document.getElementById('pre-cat-' + elementId);

            if (dom) {
                var domStyle = dom.style;
                domStyle.backgroundPosition = "50% 50%";
                domStyle.backgroundRepeat = "no-repeat";
                if (Boolean(parallax)) {
                    document.getElementById('pre-cat-' + elementId).className += ' parallax-bg';
                    //domStyle.backgroundAttachment = "fixed";
                }
                domStyle.backgroundSize = "cover";
                domStyle.backgroundImage = "url('" + url + "')";
            }
        }

        function hideImageBackground(elementId) {
            $("#pre-cat-" + elementId).hide();
        }

        @foreach ($categories as $category)
            @if (!$category->parent_id)
                @if ($category->image)
                    loadImageBackground('{{ $category->id }}', "{{ url('/') }}/{{ $category->image }}", '{{ $settings['parallax'] }}');
        @else
            hideImageBackground({{ $category->id }});
        @endif
    @endif

    @if ($settings['menus_background'])
        loadImageBackground('menus', "{{ url('/') }}/{{ $settings['menus_background'] }}", '{{ $settings['parallax'] }}');
        @else
            hideImageBackground('menus');
        @endif
        @endforeach

        //true when page is scrolling as result of menu item click
        window.scrollingClick = false;
        function goToCategory(id) {

            window.scrollingClick = true;

            $('html, body').animate({
                scrollTop: $("#cat-" + id).offset().top - 260
            }, 1000, function () {
                window.scrollingClick = false;
            });

            $("#nav-sel").val(id);
            $(".categories-nav li a").removeClass('active');
            $("#category-link-" + id).addClass('active');

        }

        function setStickyNav() {
            var navHolder = $("#nav-holder");
            $("body").css('padding-top', navHolder.height() + 'px');
            navHolder.css({
                'position': 'fixed',
                'top': 0,
                'width': '100%',
                'zIndex': 9
            });

            $("#mobile-nav-holder").css({
                'position': 'fixed',
                'top': 0,
                'width': '100%',
                'zIndex': 9
            });
        }

        function unsetStickyNav() {
            $("body").css('padding-top', '0');
            $("#nav-holder").css('position', 'static');
            $("#mobile-nav-holder").css('position', 'static');
        }


        var offsetData = [];

        function setMenusFirst() {
            var $menusLink = $("#category-link-menus").parent();
            $(".categories-nav").prepend($menusLink);

            var $menusSection = $(".menu-holder");
            $(".categories-holder").prepend($menusSection);

            var $menusOption = $("option[value='menus']");
            $("#nav-sel").prepend($menusOption);

            $menusLink.show();
            $menusSection.show();
            $menusOption.show();

            updateOffsets();

            if ($(".menu-holder").length) {
                offsetData.unshift({
                    'offset': $(".menu-holder").offset().top - 180,
                    'id': 'menus',
                });
            }
        }

        function setMenusLast() {
            var $menusLink = $("#category-link-menus").parent();
            $(".categories-nav").append($menusLink);

            var $menusSection = $(".menu-holder");
            $(".categories-holder").append($menusSection);

            var $menusOption = $("option[value='menus']");
            $("#nav-sel").append($menusOption);

            $menusLink.show();
            $menusSection.show();
            $menusOption.show();

            updateOffsets();

            if ($(".menu-holder").length) {
                offsetData.push({
                    'offset': $(".menu-holder").offset().top - 80,
                    'id': 'menus',
                });
            }
        }

        function updateOffsets() {

            offsetData = [];

            $(".categories-holder > ul > .category-holder").each(function () {

                var thisOffset = $(this).offset().top - 280;
                var thisId = $(this).attr('data-category-id');

                offsetData.push({
                    'offset': thisOffset,
                    'id': thisId
                });
            });

        }

        function removeMenus() {
            $("#category-link-menus").parent().hide();
            $(".menu-holder").hide();
            $("option[value='menus']").hide();

            updateOffsets();
        }

        $(document).ready(function () {

            @if ($settings['sticky_nav'])
                setStickyNav();
            @endif

            $("#nav-sel").change(function () {
                var id = $(this).val();
                goToCategory(id);
            });

            @if (intval($settings['display_menus']) !== 0)
                @if ($settings['display_menus'] == 1)
                    setMenusFirst();
            $("#nav-sel").val('menus');
            @else
                setMenusLast();
            @endif
        @elseif (isset($temp))
            removeMenus();
                    @endif

            var activeId = null;


            @if ($settings['sticky_nav'])
            if (window.innerWidth <= 991) {
                var navHeight = $("#mobile-nav-holder").height();
                $("body").css('padding-top', navHeight + 'px');
            }

            $(window).resize(function () {
                if (window.innerWidth <= 991) {
                    var navHeight = $("#mobile-nav-holder").height();
                    $("body").css('padding-top', navHeight + 'px');
                }
                else {
                    var navHeight = $("#nav-holder").height();
                    $("body").css('padding-top', navHeight + 'px');
                }
            });
            @endif

            $(window).scroll(function () {

                var currentPosition = document.documentElement.scrollTop || document.body.scrollTop;

                for (var i = 1; i < offsetData.length; i++) {

                    if (window.scrollingClick) {
                        break;
                    }

                    if (currentPosition < offsetData[i].offset && currentPosition >= offsetData[i - 1].offset) {
                        $(".categories-nav li a").removeClass('active');
                        $("#category-link-" + offsetData[i - 1].id).addClass('active');
                        $("#nav-sel").val(offsetData[i - 1].id);
                        activeId = offsetData[i - 1].id;
                    }
                    else if (i == offsetData.length - 1 && currentPosition >= offsetData[i].offset) {
                        $(".categories-nav li a").removeClass('active');
                        $("#category-link-" + offsetData[i].id).addClass('active');
                        $("#nav-sel").val(offsetData[i].id);
                        activeId = offsetData[i].id;
                    }
                    else if (currentPosition < offsetData[0].offset) {
                        $(".categories-nav li a").removeClass('active');
                        activeId = null;
                    }

                }
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
        $("body").attr('data-section-name', 'content');
        $("#background-img").attr('data-section-name', 'background');
        $(".allergy-icons").attr('data-section-name', 'allergies');
        $(".menu-description").attr('data-section-name', 'menu-description');

        function _setBackground(type, value) {
            if (type == 'color') {
                _setBackgroundColor(value);
            }
            else if (type == 'image') {
                _setBackgroundImage(value);
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
                newStyle += '.categories-nav li a:hover, .categories-nav li a.active, .categories-nav li a:active { background: ' + color + '; }';
                newStyle += "</style>";
                $("#extra-style").append(newStyle);
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
            var newStyle = "<style>";
            newStyle += '.categories-nav li a:hover, .categories-nav li a.active, .categories-nav li a:active { color: ' + color + '; }';
            newStyle += "</style>";
            $("#extra-style").append(newStyle);
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
                $("[data-sectionname='item-image']").show();
            }
            else {
                $("[data-sectionname='item-image']").hide();
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

        function _setScrollTop(flag) {
            //console.log('setting scroll top: '+flag);
            if (flag) {
                showScrollTop();
            }
            else {
                hideScrollTop();
            }
        }

        function _setParallax(flag) {
            if (flag) {
                $(".pre-category").css('background-attachment', 'fixed');
            }
            else {
                $(".pre-category").css('background-attachment', 'scroll');
            }
        }

        function _setStickyNav(flag) {

            if (flag) {
                setStickyNav();
            }
            else {
                unsetStickyNav();
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
	
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
            @if ($settings['background_color'])
                 background-color: {{ $settings['background_color'] }};
            @endif
             font-size: {{ $settings['content_font_size'] }}px;
        }

        .clear {
            clear: both;
        }

        @if ($settings['background_type'] == 'image' && $settings['background_image'])
		#background-img {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: -2;
        }

        @endif
	
	ul {
            padding: 0;
        }

        .categories-holder {
            width: 90%;
            max-width: 580px;
            margin: 0 auto;
            background: {{ \App\Misc::getColorAndOpacityCssValue($settings['content_background_color'], $settings['content_background_opacity']) }};
            height: 100%;
            padding: 10px;
        }

        .menu-item-holder {
            margin-bottom: 25px;
        }

        .menu-item-holder > img {
            float: left;
            width: 100px;
            padding-right: 10px;
        }

        .menu-item-content {
            display: inline-block;
        }

        .menu-item-name {
            font-family: {!! \App\Misc::cleanFontName($settings['item_name_font']) !!};
            color: {{ $settings['item_name_font_color'] }};
            font-size: {{ $settings['item_name_font_size'] }}px;
        }

        .menu-item-description {
            font-family: {!! \App\Misc::cleanFontName($settings['item_description_font']) !!};
            color: {{ $settings['item_description_font_color'] }};
            font-size: {{ $settings['item_description_font_size'] }}px;
            white-space: pre-wrap;
        }

        .menu-item-price {
            display: block;
            font-family: {!! \App\Misc::cleanFontName($settings['item_price_font']) !!};
            color: {{ $settings['item_price_font_color'] }};
            font-size: {{ $settings['item_price_font_size'] }}px;
        }

        .category-holder {
            margin-bottom: 30px;
        }

        h1 {
            font-family: {!! \App\Misc::cleanFontName($settings['main_category_font']) !!};
            font-size: {{ $settings['main_category_font_size'] }}px;
            color: {{ $settings['main_category_font_color'] }};
        }

        h2 {
            font-family: {!! \App\Misc::cleanFontName($settings['sub_category_font']) !!};
            font-size: {{ $settings['sub_category_font_size'] }}px;
            color: {{ $settings['sub_category_font_color'] }};
            margin: 15px 0;
        }

        h3, h4, h5 {
            font-size: {{ $settings['item_name_font_size'] }}px;
            color: {{ $settings['sub_sub_category_font_color'] }};
            margin-top: 8px;
        }

        .menu-item-name-suffix {
            color: {{ $settings['sub_sub_category_font_color'] }};
        }

        #nav-holder {
            position: fixed;
            top: 0;
            left: 0;
            background: {{ \App\Misc::getColorAndOpacityCssValue($settings['navigation_background_color'], $settings['navigation_background_opacity']) }};
            height: 100%;
            width: 200px;
            -webkit-transition: left .5s;
            transition: left .4s;
        }

        .categories-nav {
            padding: 0;
            list-display-type: none;
            z-index: 10;
        }

        .categories-nav li {
            list-style: none;
            line-height: 26px;
        }

        .categories-nav li a {
            font-family: {!! \App\Misc::cleanFontName($settings['navigation_font']) !!};
            font-size: {{ $settings['navigation_font_size'] }}px;
            display: block;
            padding: 5px 10px;
            color: {{ $settings['navigation_font_color'] }};
            text-decoration: none;
        }

        .categories-nav li a:hover, .categories-nav li a.active, .categories-nav li a:active {
            text-decoration: none;
            color: {{ $settings['navigation_font_color'] }};
            background: #eee;
        }

        #nav-toggle-btn {
            display: inline-block;
            position: absolute;
            right: -25px;
            top: 15px;
            visibility: hidden;
            font-size: 18px;
            background: #fff;
            color: #000;
            text-decoration: none;
            padding: 5px 5px 5px 15px;
            border-radius: 5px;
            z-index: -1;
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

        @media (max-width: 992px) {
            #nav-holder {
                left: -200px;
                background: rgba(255, 255, 255, 1);
                box-shadow: 0 0 5px #333;
            }

            #nav-holder.shown {
                left: 0px;
            }

            #nav-toggle-btn {
                visibility: visible;
            }
        }

        {!! $settings['custom_css'] !!}
    </style>
@stop

@section('body')
    <body>
    <div id="background-img">

    </div>

    @if ($settings['background_type'] == 'image' && $settings['background_image'])
        <script>
            var screenW = screen.width;
            var url = "{{ url('/') }}/{{ $settings['background_image'] }}";
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
            backgroundImgDom.style.background = 'url("' + url + '") no-repeat center center';
            backgroundImgDom.style.backgroundSize = 'cover';
        </script>
    @endif

    <div id="nav-holder" class="navigation-section">
        <ul class="categories-nav">
            @foreach ($categoriesTree as $id => $item)
                <li><a href="javascript:;" id="category-link-{{ $id }}"
                       onclick="scrollToCategory({{ $id }});">{{ $item['object']->name }}</a></li>
            @endforeach
        </ul>

        <a href="javascript:;" id="nav-toggle-btn"><span id=="nav-toggle-span">&#9776;</span></a>
    </div>

    <div class="categories-holder content-section">
        <?php \App\Misc::renderOnlineMenu($categoriesTree, true, $settings, 1); ?>
    </div>

    <script>
        function scrollToCategory(categoryId) {
            $(document.body).animate({
                'scrollTop': $("#cat-" + categoryId).offset().top - 15
            }, 600);

            if (window.innerWidth <= 991) {
                $("#nav-holder").removeClass('shown');
                $("#nav-holder").addClass('hiddenx');
            }
        }

        window.lastDevice;

        function fixSidebarVisibility() {

            if (window.innerWidth > 991) {
                if (window.lastDevice == 'mobile') {
                    $("#nav-holder").removeClass('hiddenx');
                    $("#nav-holder").addClass('shown');
                }
                window.lastDevice = 'pc';
            }
            else {
                //always hide in mobile, and show when menu is opened
                if (window.lastDevice == 'pc' || window.lastDevice == null) {
                    $("#nav-holder").removeClass('shown');
                    $("#nav-holder").addClass('hiddenx');
                }
                window.lastDevice = 'mobile';
            }
        }

        function toggleMenu() {
            if ($("#nav-holder").is('.shown')) {
                $("#nav-holder").removeClass('shown');
                $("#nav-holder").addClass('hiddenx');
            }
            else {
                $("#nav-holder").removeClass('hiddenx');
                $("#nav-holder").addClass('shown');
            }
        }

        $(document).ready(function () {

            $(".categories-nav li a").first().addClass('active');

            $(window).scroll(function () {
                var currentPosition = document.documentElement.scrollTop || document.body.scrollTop;

                var count = 0;
                var previousOffset = 0;
                var previousId = 0;

                $(".category-holder").each(function () {

                    var thisOffset = $(this).offset().top - 40;
                    var thisId = $(this).attr('data-category-id');

                    if (count > 0 && currentPosition <= thisOffset && currentPosition >= previousOffset) {
                        $(".categories-nav li a").removeClass('active');
                        $("#category-link-" + previousId).addClass('active');
                    }

                    previousOffset = thisOffset;
                    previousId = thisId;
                    count++;
                });
            });

            $("body").click(function (e) {
                if (window.innerWidth <= 991 && $("#nav-holder").hasClass('shown')) {
                    if (e.target.id == "nav-holder" || $(e.target).parents("#nav-holder").size()) {

                    } else {
                        $("#nav-holder").removeClass('shown');
                        $("#nav-holder").addClass('hiddenx');
                    }
                }
            });

            $(window).resize(function () {
                fixSidebarVisibility();
            });

            $("#nav-toggle-btn").click(function () {
                toggleMenu();
            });

            fixSidebarVisibility();
        });
    </script>
    </body>
@stop
	
	
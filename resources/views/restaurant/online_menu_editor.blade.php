<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title or "VITisch" }}</title>


    <link rel="shortcut icon" type="image/png" href="{{ url('favicon.png') }}"/>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <link href="{{ asset('/css/vifont.jquery.css') }}" rel="stylesheet">

    <link href="{{ asset('/css/admin.css') }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js does not work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        label {
            font-weight: normal;
        }

        input[type='number'], input[type='color'] {
            width: 60px;
        }

        .settings-section {
            padding: 0 10px;
            background: #333;
            color: #fff;
            transition: 0.4s ease-in-out;
        }

        .settings-section input {
            color: #000;
        }

        .nav > li > .settings-section {
            opacity: 0;
            max-height: 0;
            padding: 0;
            overflow: hidden;
        }

        .nav > li.active > .settings-section {
            opacity: 1;
            max-height: 500px;
            padding: 10px;
        }

        .control {
            text-decoration: none;
            background: #f1f1f1;
            border-radius: 1px;
            padding: 5px 5px 7px;
            margin-right: 3px;
            opacity: 0.3;
        }

        .control:hover, .control:active, .control.active {
            text-decoration: none;
            opacity: 1;
        }

        #custom-css-input {
            min-height: 300px;
        }

        header {
            background: #9c0000;
        }

        .theme-col img {
            max-width: 100%;
        }

        .theme-col a {
            display: inline-block;
            box-shadow: 0 0 5px #666;
        }

        .theme-col a.active {
            border: 2px solid green;
        }
    </style>
</head>

<body>
<section class="main-wrapper">
    <header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-sm-5 col-xs-4">
                    <div class="logo-holder">

                        <a href="javascript:;" onclick="toggleMenu()"
                           id="mobile-menu"><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span></a>

                        <a href="javascript:;" id="restaurant-logo">
                            {{ trans('menu.online_menu_editor') }}
                        </a>

                    </div>
                </div>

                <div class="col-md-4 col-sm-4 col-xs-5">

                </div>

                <div class="col-md-2 col-sm-3 col-xs-3">
                    <div class="btn-holder">

                    </div>
                </div>
            </div>
        </div>
    </header>

    {!! Form::open(['method' => 'post', 'action' => 'OnlineMenuController@submitTemp', 'id' => 'edit-form']) !!}

    <div class="side-bar">
        <nav class="navbar navbar-default">
            <ul class="nav nav-stacked" id="accordion1">


                <li><a href="javascript:;" id="theme-btn"><i
                                class="fa fa-angle-double-right"></i> {{ trans('menu.themes') }}</a>
                    <input type="hidden" name="theme_id" value="{{ $settings['theme_id'] }}"/>
                </li>

                <li><a href="javascript:;"><i class="fa fa-angle-double-right"></i> {{ trans('menu.general_settings') }}
                    </a>
                    <div class="settings-section" data-section-name="general" id="general-settings-section">

                        <div class="form-group">
                            {!! Form::radio('display_allergies', 1, (bool)$settings['display_allergies']) !!} {{ trans('menu.display_allergies') }}
                            <br>
                            {!! Form::radio('display_allergies', 0, (bool)(!$settings['display_allergies'])) !!} {{ trans('menu.hide_allergies') }}
                        </div>

                        @if ($activeTheme->has_sticky_nav)
                            <div class="form-group">
                                {!! Form::radio('sticky_nav', 1, (bool)$settings['sticky_nav']) !!} {{ trans('menu.enable_sticky_nav') }}
                                <br>
                                {!! Form::radio('sticky_nav', 0, (bool)(!$settings['sticky_nav'])) !!} {{ trans('menu.disable_sticky_nav') }}
                            </div>
                        @endif

                        @if ($activeTheme->has_scroll_top)
                            <div class="form-group">
                                {!! Form::radio('scroll_top', 1, (bool)$settings['scroll_top']) !!} {{ trans('menu.display_scroll_top_btn') }}
                                <br>
                                {!! Form::radio('scroll_top', 0, (bool)(!$settings['scroll_top'])) !!} {{ trans('menu.hide_scroll_top_btn') }}
                            </div>
                        @endif

                        @if ($activeTheme->has_parallax)
                            <div class="form-group">
                                {!! Form::radio('parallax', 1, (bool)$settings['parallax']) !!} {{ trans('menu.enable_parallax_effect') }}
                                <br>
                                {!! Form::radio('parallax', 0, (bool)(!$settings['parallax'])) !!} {{ trans('menu.disable_parallax_effect') }}
                            </div>
                        @endif


                        <?php
                        /*
                        <div class="form-group">
                            {!! Form::label('content_font', trans('menu.content_font')) !!}<br>
                            <input type="hidden" id="content-font-input" name="content_font" value="{{ $settings['content_font'] }}" />
                            <a href="javascript:;" data-input-id="content-font-input" class="font-link">{{ trans('menu.change_font') }}</a>
                        </div>
                        */
                        ?>
                    </div>
                </li>

                @if ($activeTheme->has_background)
                    <li><a href="javascript:;"><i class="fa fa-angle-double-right"></i> {{ trans('menu.background') }}
                        </a>
                        <div class="settings-section" data-section-name="background" id="background-section">

                            @if ($activeTheme->has_background_color)
                                <div class="form-group">
                                    {!! Form::radio('background_type', 'color', (bool)($settings['background_type'] == 'color')) !!} {{ trans('menu.color') }}
                                    <br>
                                    <div class="background-sub-group" data-group-name="color"
                                         style="{{ $settings['background_type'] == 'color' ? '' : 'display:none' }}">
                                        {!! Form::input('color', 'background_color', $settings['background_color']) !!}
                                    </div>
                                </div>
                            @endif

                            @if ($activeTheme->has_background_image)
                                <div class="form-group">
                                    {!! Form::radio('background_type', 'image', (bool)($settings['background_type'] == 'image')) !!} {{ trans('menu.photo_album') }}
                                    <br>
                                    <div class="background-sub-group" data-group-name="image"
                                         style="{{ $settings['background_type'] == 'image' ? '' : 'display:none;' }}">
                                        <input type="hidden" name="background_image" id="menu-image-input"
                                               value="{{ $settings['background_image'] }}"/>
                                        <a href="javascript:;" id="photo-album-btn">{{ trans('menu.choose_photo') }}</a>
                                    </div>
                                </div>
                            @endif

                            @if ($activeTheme->has_use_category_images)
                                <div class="form-group">
                                    {!! Form::radio('background_type', 'inherit', (bool)($settings['background_type'] == 'inherit')) !!} {{ trans('menu.use_category_images') }}
                                    <br>
                                </div>
                            @endif
                        </div>
                    </li>
                @endif


                <li><a href="javascript:;"><i
                                class="fa fa-angle-double-right"></i> {{ trans('menu.navigation_settings') }}</a>

                    <div class="settings-section" data-section-name="navigation" id="navigation-settings-section">

                        <div class="form-group">
                            <input type="hidden" name="navigation_bold" data-format="bold"
                                   value="{{ (bool)$settings['navigation_bold'] }}"/>
                            <input type="hidden" name="navigation_italic" data-format="italic"
                                   value="{{ (bool)$settings['navigation_italic'] }}"/>

                            {!! \App\Misc::printFormattingControls([
                                'bold'	=>	$settings['navigation_bold'],
                                'italic' =>	$settings['navigation_italic'],
                            ]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('navigation_font_size', trans('menu.font_size')) !!}
                            <br>
                            {!! Form::input('number', 'navigation_font_size', $settings['navigation_font_size'], ['class' => 'font-size-input']) !!}
                            px
                        </div>

                        <div class="form-group">
                            {!! Form::label('navigation_font_color', trans('menu.font_color')) !!}
                            <br>
                            {!! Form::input('color', 'navigation_font_color', $settings['navigation_font_color'], ['class' => 'font-color-input']) !!}
                        </div>

                        @if ($activeTheme->has_navigation_background_color)
                            <div class="form-group">
                                {!! Form::label('navigation_background_color', trans('menu.background_color')) !!}
                                <br>
                                {!! Form::input('color', 'navigation_background_color', $settings['navigation_background_color']) !!}
                            </div>
                        @endif

                        @if ($activeTheme->has_navigation_background_opacity)
                            <div class="form-group">
                                {!! Form::label('navigation_background_opacity', trans('menu.background_opacity')) !!}
                                (0 - 100)<br>
                                {!! Form::input('number', 'navigation_background_opacity', $settings['navigation_background_opacity'], ['class' => 'small-input']) !!}
                                %
                            </div>
                        @endif

                        <div class="form-group">
                            {!! Form::label('navigation_font', trans('menu.font')) !!}<br>
                            <input type="hidden" id="navigation-font-input" name="navigation_font" class="font-input"
                                   value="{{ $settings['navigation_font'] }}"/>
                            <a href="javascript:;" data-input-id="navigation-font-input"
                               class="font-link">{{ trans('menu.change_font') }}</a>
                        </div>


                    </div>
                </li>

                <li><a href="javascript:;"><i class="fa fa-angle-double-right"></i> {{ trans('menu.content_settings') }}
                    </a>
                    <div class="settings-section" data-section-name="content" id="content-settings-section">

                        <div class="form-group">
                            {!! Form::label('content_background_color', trans('menu.background_color')) !!}<br>
                            {!! Form::input('color', 'content_background_color', $settings['content_background_color']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('content_background_opacity', trans('menu.background_opacity')) !!}<br>
                            {!! Form::input('number', 'content_background_opacity', $settings['content_background_opacity'], ['class' => 'small-input']) !!}
                            %
                        </div>
                    </div>
                </li>

                <li><a href="javascript:;"><i
                                class="fa fa-angle-double-right"></i> {{ trans('menu.main_category_title') }}</a>

                    <div class="settings-section" data-section-name="main-category-title"
                         id="main-category-title-section">

                        <div class="form-group">
                            <input type="hidden" name="main_category_alignment"
                                   value="{{ $settings['main_category_alignment'] }}"/>
                            {!! \App\Misc::printAlignmentControls($settings['main_category_alignment']) !!}
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="main_category_bold" data-format="bold"
                                   value="{{ (bool)$settings['main_category_bold'] }}"/>
                            <input type="hidden" name="main_category_italic" data-format="italic"
                                   value="{{ (bool)$settings['main_category_italic'] }}"/>
                            <input type="hidden" name="main_category_underlined" data-format="underlined"
                                   value="{{ (bool)$settings['main_category_underlined'] }}"/>

                            {!! \App\Misc::printFormattingControls([
                                'bold'	=>	$settings['main_category_bold'],
                                'italic' =>	$settings['main_category_italic'],
                                'underlined' => $settings['main_category_underlined']
                            ]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('main_category_font_size', trans('menu.font_size')) !!} <br>
                            {!! Form::input('number', 'main_category_font_size', $settings['main_category_font_size'], ['class' => 'font-size-input']) !!}
                            px
                        </div>

                        <div class="form-group">
                            {!! Form::label('main_category_font_color', trans('menu.font_color')) !!}
                            <br>
                            {!! Form::input('color', 'main_category_font_color', $settings['main_category_font_color'], ['class' => 'font-color-input']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('main_category_font', trans('menu.font')) !!}<br>
                            <input type="hidden" id="main-category-font-input" name="main_category_font"
                                   class="font-input" value="{{ $settings['main_category_font'] }}"/>
                            <a href="javascript:;" data-input-id="main-category-font-input"
                               class="font-link">{{ trans('menu.change_font') }}</a>
                        </div>
                    </div>
                </li>

                <li><a href="javascript:;"><i
                                class="fa fa-angle-double-right"></i> {{ trans('menu.sub_category_title') }}</a>

                    <div class="settings-section" data-section-name="sub-category-title"
                         id="sub-category-title-section">

                        <div class="form-group">
                            <input type="hidden" name="sub_category_alignment"
                                   value="{{ $settings['sub_category_alignment'] }}"/>
                            {!! \App\Misc::printAlignmentControls($settings['sub_category_alignment']) !!}
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="sub_category_bold" data-format="bold"
                                   value="{{ (bool)$settings['sub_category_bold'] }}"/>
                            <input type="hidden" name="sub_category_italic" data-format="italic"
                                   value="{{ (bool)$settings['sub_category_italic'] }}"/>
                            <input type="hidden" name="sub_category_underlined" data-format="underlined"
                                   value="{{ (bool)$settings['sub_category_underlined'] }}"/>

                            {!! \App\Misc::printFormattingControls([
                                'bold'	=>	$settings['sub_category_bold'],
                                'italic' =>	$settings['sub_category_italic'],
                                'underlined' => $settings['sub_category_underlined']
                            ]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('sub_category_font_size', trans('menu.font_size')) !!}
                            <br>
                            {!! Form::input('number', 'sub_category_font_size', $settings['sub_category_font_size'], ['class' => 'font-size-input']) !!}
                            px
                        </div>

                        <div class="form-group">
                            {!! Form::label('sub_category_font_color', trans('menu.font_color')) !!}
                            <br>
                            {!! Form::input('color', 'sub_category_font_color', $settings['sub_category_font_color'], ['class' => 'font-color-input']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('sub_category_font', trans('menu.font')) !!}<br>
                            <input type="hidden" id="sub-category-font-input" name="sub_category_font"
                                   class="font-input" value="{{ $settings['sub_category_font'] }}"/>
                            <a href="javascript:;" data-input-id="sub-category-font-input"
                               class="font-link">{{ trans('menu.change_font') }}</a>
                        </div>
                    </div>
                </li>

                <li><a href="javascript:;"><i
                                class="fa fa-angle-double-right"></i> {{ trans('menu.sub_sub_category_title') }}</a>

                    <div class="settings-section" data-section-name="sub-sub-category-title"
                         id="sub-sub-category-title-section">


                        <div class="form-group">
                            <input type="hidden" name="sub_sub_category_bold" data-format="bold"
                                   value="{{ (bool)$settings['sub_sub_category_bold'] }}"/>
                            <input type="hidden" name="sub_sub_category_italic" data-format="italic"
                                   value="{{ (bool)$settings['sub_sub_category_italic'] }}"/>
                            <input type="hidden" name="sub_sub_category_underlined" data-format="underlined"
                                   value="{{ (bool)$settings['sub_sub_category_underlined'] }}"/>

                            {!! \App\Misc::printFormattingControls([
                                'bold'	=>	$settings['sub_sub_category_bold'],
                                'italic' =>	$settings['sub_sub_category_italic'],
                                'underlined' => $settings['sub_sub_category_underlined']
                            ]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('sub_sub_category_font_color', trans('menu.font_color')) !!}
                            <br>
                            {!! Form::input('color', 'sub_sub_category_font_color', $settings['sub_sub_category_font_color'], ['class' => 'font-color-input']) !!}
                        </div>
                    </div>
                </li>

                <li><a href="javascript:;"><i class="fa fa-angle-double-right"></i> {{ trans('menu.item_name') }}</a>
                    <div class="settings-section" data-section-name="item-name" id="item-name-section">

                        <div class="form-group">
                            <input type="hidden" name="item_name_bold" data-format="bold"
                                   value="{{ (bool)$settings['item_name_bold'] }}"/>
                            <input type="hidden" name="item_name_italic" data-format="italic"
                                   value="{{ (bool)$settings['item_name_italic'] }}"/>
                            <input type="hidden" name="item_name_underlined" data-format="underlined"
                                   value="{{ (bool)$settings['item_name_underlined'] }}"/>

                            {!! \App\Misc::printFormattingControls([
                                'bold'	=>	$settings['item_name_bold'],
                                'italic' =>	$settings['item_name_italic'],
                                'underlined' => $settings['item_name_underlined']
                            ]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('item_name_font_size', trans('menu.font_size')) !!}<br>
                            {!! Form::input('number', 'item_name_font_size', $settings['item_name_font_size'], ['class' => 'font-size-input']) !!}
                            px
                        </div>

                        <div class="form-group">
                            {!! Form::label('item_name_font_color', trans('menu.font_color')) !!}<br>
                            {!! Form::input('color', 'item_name_font_color', $settings['item_name_font_color'], ['class' => 'font-color-input']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('item_name_font', trans('menu.font')) !!}<br>
                            <input type="hidden" id="item-name-font-input" name="item_name_font" class="font-input"
                                   value="{{ $settings['item_name_font'] }}"/>
                            <a href="javascript:;" data-input-id="item-name-font-input"
                               class="font-link">{{ trans('menu.change_font') }}</a>
                        </div>
                    </div>
                </li>

                <li><a href="javascript:;"><i class="fa fa-angle-double-right"></i> {{ trans('menu.item_image') }}</a>
                    <div class="settings-section" data-section-name="item-image" id="item-image-section">

                        <div class="form-group">
                            {!! Form::radio('display_images', 1, (bool)$settings['display_images']) !!} {{ trans('menu.display_images') }}
                            <br>
                            {!! Form::radio('display_images', 0, (bool)(!$settings['display_images'])) !!} {{ trans('menu.hide_images') }}
                        </div>

                        <div class="form-group">
                            {!! Form::label('image_width', trans('menu.image_width')) !!}<br>
                            {!! Form::input('number', 'item_image_width', $settings['item_image_width']) !!}
                        </div>
                    </div>
                </li>


                <li><a href="javascript:;"><i class="fa fa-angle-double-right"></i> {{ trans('menu.item_description') }}
                    </a>
                    <div class="settings-section" data-section-name="item-description" id="item-description-section">

                        <div class="form-group">
                            <input type="hidden" name="item_description_bold" data-format="bold"
                                   value="{{ (bool)$settings['item_description_bold'] }}"/>
                            <input type="hidden" name="item_description_italic" data-format="italic"
                                   value="{{ (bool)$settings['item_description_italic'] }}"/>
                            <input type="hidden" name="item_description_underlined" data-format="underlined"
                                   value="{{ (bool)$settings['item_description_underlined'] }}"/>

                            {!! \App\Misc::printFormattingControls([
                                'bold'	=>	$settings['item_description_bold'],
                                'italic' =>	$settings['item_description_italic'],
                                'underlined' => $settings['item_description_underlined']
                            ]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('item_description_font_size', trans('menu.font_size')) !!}<br>
                            {!! Form::input('number', 'item_description_font_size', $settings['item_description_font_size'], ['class' => 'font-size-input']) !!}
                            px
                        </div>

                        <div class="form-group">
                            {!! Form::label('item_description_font_color', trans('menu.font_color')) !!}<br>
                            {!! Form::input('color', 'item_description_font_color', $settings['item_description_font_color'], ['class' => 'font-color-input']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('description_font', trans('menu.font')) !!}<br>
                            <input type="hidden" id="item-description-font-input" name="item_description_font"
                                   class="font-input" value="{{ $settings['item_description_font'] }}"/>
                            <a href="javascript:;" data-input-id="item-description-font-input"
                               class="font-link">{{ trans('menu.change_font') }}</a>
                        </div>
                    </div>
                </li>

                <li><a href="javascript:;"><i class="fa fa-angle-double-right"></i> {{ trans('menu.item_price') }}</a>
                    <div class="settings-section" data-section-name="item-price" id="item-price-section">

                        <div class="form-group">
                            {!! Form::radio('display_prices', 1, (bool)$settings['display_prices']) !!} {{ trans('menu.display_prices') }}
                            <br>
                            {!! Form::radio('display_prices', 0, (bool)(!$settings['display_prices'])) !!} {{ trans('menu.hide_prices') }}
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="item_price_bold" data-format="bold"
                                   value="{{ (bool)$settings['item_price_bold'] }}"/>
                            <input type="hidden" name="item_price_italic" data-format="italic"
                                   value="{{ (bool)$settings['item_price_italic'] }}"/>
                            <input type="hidden" name="item_price_underlined" data-format="underlined"
                                   value="{{ (bool)$settings['item_price_underlined'] }}"/>

                            {!! \App\Misc::printFormattingControls([
                                'bold'	=>	$settings['item_price_bold'],
                                'italic' =>	$settings['item_price_italic'],
                                'underlined' => $settings['item_price_underlined']
                            ]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('item_price_font_size', trans('menu.font_size')) !!}<br>
                            {!! Form::input('number', 'item_price_font_size', $settings['item_price_font_size'], ['class' => 'font-size-input']) !!}
                            px
                        </div>

                        <div class="form-group">
                            {!! Form::label('item_price_font_color', trans('menu.font_color')) !!}<br>
                            {!! Form::input('color', 'item_price_font_color', $settings['item_price_font_color'], ['class' => 'font-color-input']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('item_price_font', trans('menu.font')) !!}<br>
                            <input type="hidden" id="item-price-font-input" name="item_price_font" class="font-input"
                                   value="{{ $settings['item_price_font'] }}"/>
                            <a href="javascript:;" data-input-id="item-price-font-input"
                               class="font-link">{{ trans('menu.change_font') }}</a>
                        </div>
                    </div>
                </li>


                <li><a href="javascript:;"><i class="fa fa-angle-double-right"></i> {{ trans('restaurant.menus') }}</a>
                    <div class="settings-section" data-section-name="menu-description" id="menu-description-section">

                        <div class="form-group">
                            {!! Form::radio('display_menus', -1, intval($settings['display_menus']) == -1) !!} {{ trans('menu.display_menus_last') }}
                            <br>
                            {!! Form::radio('display_menus', 1, intval($settings['display_menus']) == 1) !!} {{ trans('menu.display_menus_first') }}
                            <br>
                            {!! Form::radio('display_menus', 0, intval($settings['display_menus']) == 0) !!} {{ trans('menu.hide_menus') }}
                        </div>

                        <hr>

                        <div class="form-group">
                            {!! Form::radio('display_menu_items_price', 1, (bool)$settings['display_menu_items_price']) !!} {{ trans('menu.display_menu_items_price') }}
                            <br>
                            {!! Form::radio('display_menu_items_price', 0, (bool)!($settings['display_menu_items_price'])) !!} {{ trans('menu.hide_menu_items_price') }}
                        </div>

                        <hr>

                        {!! trans('menu.menu_description_style') !!}: <br><br>

                        <div class="form-group">
                            <input type="hidden" name="menu_description_bold" data-format="bold"
                                   value="{{ (bool)$settings['menu_description_bold'] }}"/>
                            <input type="hidden" name="menu_description_italic" data-format="italic"
                                   value="{{ (bool)$settings['menu_description_italic'] }}"/>
                            <input type="hidden" name="menu_description_underlined" data-format="underlined"
                                   value="{{ (bool)$settings['menu_description_underlined'] }}"/>

                            {!! \App\Misc::printFormattingControls([
                                'bold'	=>	$settings['menu_description_bold'],
                                'italic' =>	$settings['menu_description_italic'],
                                'underlined' => $settings['menu_description_underlined']
                            ]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('menu_description_font_size', trans('menu.font_size')) !!}<br>
                            {!! Form::input('number', 'menu_description_font_size', $settings['menu_description_font_size'], ['class' => 'font-size-input']) !!}
                            px
                        </div>

                        <div class="form-group">
                            {!! Form::label('menu_description_font_color', trans('menu.font_color')) !!}<br>
                            {!! Form::input('color', 'menu_description_font_color', $settings['menu_description_font_color'], ['class' => 'font-color-input']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('menu_descrption_font', trans('menu.font')) !!}<br>
                            <input type="hidden" id="menu-description-font-input" name="menu_description_font"
                                   class="font-input" value="{{ $settings['menu_description_font'] }}"/>
                            <a href="javascript:;" data-input-id="menu-description-font-input"
                               class="font-link">{{ trans('menu.change_font') }}</a>
                        </div>

                        <?php
                        /*
                        <div class="form-group">
                            {!! Form::radio('display_menu_prices', 1, (bool)$settings['display_menu_prices']) !!} {{ trans('menu.display_menu_prices') }}
                            <br>
                            {!! Form::radio('display_menu_prices', 0, (bool)(!$settings['display_menu_prices'])) !!} {{ trans('menu.hide_menu_prices') }}
                        </div>
                        */
                        ?>
                    </div>
                </li>

                <li><a href="javascript:;" id="custom-css-link"><i
                                class="fa fa-angle-double-right"></i> {{ trans('menu.custom_css') }}</a></li>


            </ul>
        </nav>
    </div>

    <!-- Custom CSS Modal -->
    @include('partials.modal_top', ['modalId' => 'css-dialog'])

    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>{{ trans('menu.custom_css') }}</h3>
    </div>

    <div class="modal-body">
        <textarea name="custom_css" id="custom-css-input"
                  class="form-control">{!! $settings['custom_css'] !!}</textarea>
    </div>

    <div class="modal-footer">
        <a href="javascript:;" class="btn btn-primary" onclick="submitCss();">Submit</a>
    </div>

    @include('partials.modal_bottom')


    <div class="content-section">

        <div class="content-holder">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        @include('partials.flash')
                        @include('partials.errors')
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="btns-holder">
                          
                            <div class="checkbox-inline">
                                {!! Form::checkbox('url_activated', true, $settings['url_activated'], ['id' => 'url-activated'] )  !!}
                                {!! Form::label('url_activated', trans('menu.display_online_menu_url')) !!}
                            </div>
                            
                            
                            <a href="javascript:;"
                               class="btn btn-primary"
                               id="get-link-btn"><i class="fa fa-link"></i> {{ trans('menu.get_link') }}</a>
			    
                            <?php
                            /*
                            <a href="javascript:;" class="btn btn-warning" id="preview-btn"><i class="fa fa-refresh" aria-hidden="true"></i> {{ trans('menu.preview_changes') }}</a>
                            */
                            ?>

                            <span id="preview-loader" style="display:none;" class="loader">
                                <img src="{{ asset('img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}..."
                                     style="margin:0 10px;"/></span>
                            <a href="{{ action('OnlineMenuController@saveChanges') }}"
                               class="menu-editor-save-button btn btn-primary disabled"
                               style="float:right;">{{ trans('menu.save_and_publish') }}</a>

                            <a href="javascript:;" onclick="resetSettings();"
                               class="menu-editor-reset-button disabled btn btn-warning"
                               style="float:right; margin-right:5px;">
                                <i class="fa fa-undo"></i> {{ trans('menu.reset_settings') }}</a>
                        </div>

                        <iframe src="{{ action('OnlineMenuController@temp') }}"
                                style="border:1px solid #000; width:100%; height:800px; margin-top:10px;"
                                id="preview-iframe"></iframe>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {!! Form::close() !!}

</section>


<!-- Modals -->
@include('partials.modal_top', ['modalId' => 'get-link-dialog'])

<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>{{ trans('menu.online_menu_link') }}</h3>
</div>

<div class="modal-body">

    <a href="{{ url('/online-menu') }}" target="_blank">{{ url('/online-menu') }}</a>

    <div class="clear"></div>
</div>

@include('partials.modal_bottom')

<div class="modal" id="theme-dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <a class="close" data-dismiss="modal">×</a>
                <h3>{{ trans('menu.choose_theme') }}</h3>
            </div>

            <div class="modal-body">

                @foreach ($themes as $theme)
                    <div class="col-md-4 col-sm-6 col-xs-12 theme-col">
                        <a href="javascript:;" onclick="selectTheme({{ $theme->id }});"
                           class="{{ $settings['theme_id'] == $theme->id ? 'active' : '' }}">
                            <img src="{{ asset('img/menu/themes/'.$theme->name.'.png') }}" alt="{{ $theme->name }}"/>
                        </a>
                    </div>
                @endforeach

                <div class="clear"></div>
            </div>

        @include('partials.modal_bottom')

        <!-- Font Modal -->
            @include('partials.modal_top', ['modalId' => 'font-dialog'])

            <div class="modal-header">
                <a class="close" data-dismiss="modal">×</a>
                <h3>{{ trans('menu.change_font_title') }}</h3>
            </div>

            <div class="modal-body">
                <div id="fonts-filter">
                    <input type="text" name="font_filter" id="font-filter-input" class="form-control"
                           placeholder="{{ trans('general.filter') }}" style="max-width:250px;"/>
                </div>

                <hr>

                <div id="fonts-widget"></div>
            </div>

        @include('partials.modal_bottom')


        <!-- Delete Photo Modal -->
            <!-- Photo album modal -->
        @include('restaurant.menu_album_partials.album_modals')

        <!-- Scripts -->
            <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="{{ asset('/js/vifont.jquery.js') }}"></script>

            <script>
                $(window).resize(function () {
                    fixSidebarVisibility();
                });

                window.lastDevice;

                function toggleMenu() {
                    var sideBar = $('.side-bar');
                    if (sideBar.is('.shown')) {
                        sideBar.css("left", -250);
                        sideBar.removeClass('shown');
                        sideBar.addClass('hidden');
                        $(".side-bar-footer").hide();
                    }
                    else {
                        sideBar.css("left", 0);
                        sideBar.removeClass('hidden');
                        sideBar.addClass('shown');

                        if ($(window).height() > 400) {
                            $(".side-bar-footer").show();
                        }
                    }
                }

                function fixSidebarVisibility() {
                    var sideBar = $('.side-bar');
                    var sideBarFooter = $('.side-bar-footer');
                    if ($(window).width() > 991) {
                        if (window.lastDevice == 'mobile') {
                            sideBar.css("left", 0).removeClass('hidden').addClass('shown');

                            if ($(window).height() > 400) {
                                sideBarFooter.show();
                            }
                        }
                        window.lastDevice = 'pc';
                    }
                    else {
                        //always hide in mobile, and show when menu is opened
                        sideBarFooter.hide();
                        if (window.lastDevice == 'pc' || window.lastDevice == null) {
                            sideBar.css("left", -250).removeClass('shown').addClass('hidden');
                            sideBarFooter.hide();
                        }
                        window.lastDevice = 'mobile';
                    }
                }

                function selectTheme(themeId) {
                    var themeIdInput = $("input[name='theme_id']");
                    var currentThemeId = themeIdInput.val();

                    if (themeId != currentThemeId) {
                        themeIdInput.val(themeId);
                        $("#theme-dialog").modal('hide');
                        //$("#preview-btn").click();
                        //$("#preview-btn").addClass('disabled');
                        //$("#preview-btn").prop('disabled', true);
                        $("#preview-loader").show();
                        $("#edit-form").submit();
                    }
                }

                function submitCss() {
                    $("#css-dialog").modal('hide');
                    $("#preview-loader").show();
                    $("#edit-form").submit();
                }

                function resetSettings() {
                    if (confirm('{!! trans('menu.confirm_reset_settings_msg') !!}')) {
                        window.location.assign('{{ action('OnlineMenuController@resetSettings') }}');
                    }
                }

                @include('restaurant.menu_album_partials.scripts')


                        window.settingsChanged = false;

                function saveSettings() {

                    if (window.settingsChanged) {

                        window.settingsChanged = false;

                        var form = $("#edit-form");

                        var formData = form.serialize();
                        var url = form.attr('action');

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: formData
                        }).done(function (data) {
                            console.log('settings updated');
                            $('.menu-editor-save-button').removeClass('disabled');
                            $('.menu-editor-reset-button').removeClass('disabled');
                        }).fail(function (xhr) {
                            alert('{{ trans('general.server_communication_error_msg') }}');
                            location.reload();
                        });
                    }
                    else {
                        //console.log('no changes');
                    }
                    
                }


                $(function () {

                    $("#get-link-btn").click(function () {
                        $("#get-link-dialog").modal('show');
                    });

                    var saveSettingsTimer = setInterval(saveSettings, 2000);

                    fixSidebarVisibility();

                    $(".nav li > a").click(function () {

                        var selectedSection = $(this).parent().find('.settings-section').attr('data-section-name');
                        var previewIframe = $("#preview-iframe");

                        if ($(this).parent().hasClass('active')) {

                            $(this).parent().removeClass('active');
                            $(".nav li .fa-angle-double-down").addClass('fa-angle-double-right').removeClass('fa-angle-double-down');
                            previewIframe[0].contentWindow._unhighlightSections();
                        }
                        else {
                            $(".nav li").removeClass('active');
                            $(".nav li .fa-angle-double-down").addClass('fa-angle-double-right').removeClass('fa-angle-double-down');

                            previewIframe[0].contentWindow._unhighlightSections();
                            previewIframe[0].contentWindow._highlightSection(selectedSection);

                            $(this).parent().addClass('active');
                            $(this).parent().find('.fa').removeClass('fa-angle-double-right').addClass('fa-angle-double-down');
                        }
                    });


                    $("input[name='background_type']").change(function () {
                        var thisVal = $(this).val();
                        $(".background-sub-group").hide();
                        $(".background-sub-group[data-group-name='" + thisVal + "']").show();

                        var innerValue = null;
                        if (thisVal == 'color') {
                            innerValue = $("input[name='background_color']").val();
                        }
                        else if (thisVal == 'image') {
                            innerValue = '{{ url('/') }}/' + $("input[name='background_image']").val();
                        }

                        $("#preview-iframe")[0].contentWindow._setBackground(thisVal, innerValue);
                        //menuHandler.setBackground(thisVal, innerValue);
                        window.settingsChanged = true;
                    });

                    $("#theme-btn").click(function () {
                        $("#theme-dialog").modal('show');
                    });

                    $("#photo-album-btn").click(function () {
                        $("#photo-album-dialog").modal('show');
                    });

                    var fontWidget = $("#fonts-widget").vifont({
                        'fontHeight': 45,
                        'active': '{{ trans('menu.active') }}',
                        'selectFont': '{{ trans('menu.select_font') }}',
                    });

                    $(".font-link").click(function () {
                        var inputId = $(this).attr('data-input-id');
                        var $input = $("#" + inputId);
                        var oldFont = $input.val();
                        fontWidget.setSetting('activeFont', oldFont);
                        fontWidget.setSetting('fontPicked', function (fontName) {

                            $input.val(fontName);

                            if ($(".font-input[value='" + oldFont + "']").length == 0) {
                                $("#preview-iframe")[0].contentWindow._removeFont(oldFont);
                            }

                            $input.trigger('change');
                            $("#font-dialog").modal('hide');
                        });
                        $("#font-dialog").modal('show');
                    });

                    $("#custom-css-link").click(function () {
                        $("#css-dialog").modal('show');
                    });

                    $("input[name='background_color']").change(function () {
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setBackgroundColor($(this).val());
                    });

                    $("input[name='background_image']").change(function () {
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setBackgroundImage('{{ url('/') }}/' + $(this).val());
                    });

                    $(".format-control").click(function () {

                        var option = $(this).attr('data-format');

                        if ($(this).hasClass('active')) {
                            flag = false;
                            $(this).removeClass('active');
                            $(this).parent().find("input[data-format='" + option + "']").val(0);
                        }
                        else {
                            flag = true;
                            $(this).addClass('active');
                            $(this).parent().find("input[data-format='" + option + "']").val(1);
                        }

                        var section = $(this).parent().parent().attr('data-section-name');

                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._formatElement(section, option, flag);
                    });


                    $(".alignment-control").click(function () {

                        var value = $(this).attr('data-alignment');

                        if ($(this).hasClass('active')) {
                            return;
                        }
                        else {
                            $(".alignment-control").removeClass('active');
                            $(this).addClass('active');
                            $(this).parent().find("input").val(value);
                        }

                        var section = $(this).parent().parent().attr('data-section-name');
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._alignElement(section, value);
                    });

                    $(".font-size-input").on('keyup change', function () {
                        fontSizeChanged($(this));

                    });

                    function fontSizeChanged($input) {
                        var size = $input.val();
                        var section = $input.parent().parent().attr('data-section-name');
                        //console.log(section+' '+size);
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setFontSize(section, size);
                    }

                    $(".font-color-input").change(function () {
                        var color = $(this).val();
                        var section = $(this).parent().parent().attr('data-section-name');
                        //console.log(section+' '+color);
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setFontColor(section, color);
                    });

                    $("input[name='content_background_color']").change(function () {
                        var color = $(this).val();
                        var opacity = $("input[name='content_background_opacity']").val();
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setContentBackground(color, opacity);
                    });


                    $("input[name='content_background_opacity']").on('keyup change', function () {
                        backgroundOpacityChanged($(this));
                    });

                    function backgroundOpacityChanged($input) {
                        var opacity = $input.val();
                        var color = $("input[name='content_background_color']").val();
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setContentBackground(color, opacity);
                    }


                    $("input[name='navigation_background_color']").change(function () {
                        var color = $(this).val();
                        var opacity = $("input[name='navigation_background_opacity']").val();
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setNavigationBackground(color, opacity);
                    });


                    $("input[name='navigation_background_opacity']").on('keyup change', function () {
                        navigationOpacityChanged($(this));
                    });

                    function navigationOpacityChanged($input) {
                        var opacity = $input.val();
                        var color = $("input[name='navigation_background_color']").val();
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setNavigationBackground(color, opacity);
                    }

                    $("[name='sticky_nav']").change(function () {
                        var flag = Boolean(+$(this).val());
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setStickyNav(flag);
                    });

                    $("[name='display_prices']").change(function () {
                        var flag = Boolean(+$(this).val());
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setDisplayPrices(flag);
                    });

                    $("[name='display_images']").change(function () {
                        var flag = Boolean(+$(this).val());
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setDisplayImages(flag);
                    });

                    $("[name='display_allergies']").change(function () {
                        var flag = Boolean(+$(this).val());
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setDisplayAllergies(flag);
                    });

                    $("[name='scroll_top']").change(function () {
                        var flag = Boolean(+$(this).val());
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setScrollTop(flag);
                    });

                    $("[name='parallax']").change(function () {
                        var flag = Boolean(+$(this).val());
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setParallax(flag);
                    });

                    $("[name='display_menus']").change(function () {
                        var flag = parseInt($(this).val());
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setDisplayMenus(flag);
                    });

                    $("[name='display_menu_items_price']").change(function () {
                        var flag = Boolean(+$(this).val());
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setDisplayMenuItemsPrice(flag);
                    });

                    $("input[name='item_image_width']").on('keyup change', function () {
                        itemImageWidthChanged($(this));
                    });

                    $("input[name='url_activated']").change(function () {
                        window.settingsChanged = true;
                    });
                    

                    function itemImageWidthChanged($input) {
                        var width = parseInt($input.val());
                        window.settingsChanged = true;
                        $("#preview-iframe")[0].contentWindow._setItemImageWidth(width);
                    }

                    $(".font-input").change(function () {

                        var section = $(this).parent().parent().attr('data-section-name');
                        var font = $(this).val();
                        var previewIframe = $("#preview-iframe");


                        window.settingsChanged = true;
                        if ($(".font-input[value='" + font + "']").length == 1) {
                            //console.log('calling import font');
                            previewIframe[0].contentWindow._importFont(font);
                        }

                        previewIframe[0].contentWindow._setFont(section, font);
                    });

                });

                $("div.alert").not('.alert-important').delay(3000).slideUp(300);
            </script>

</body>
</html>
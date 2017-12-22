@extends('online.preorder.master')

@section('head')
    <style>
        .menu-holder ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .menu-holder li {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .category-holder h1 {
            display: none;
            font-size: 24px;
        }

        #cat-menus .category-holder h1 {
            display: block;
            text-align: center;
        }

        .menu-item-content {
            position: relative;
        }

        .menu-description {
            text-align: center;
            margin-bottom: 22px;
        }

        .category-holder h2 {
            font-size: 20px;
            color: #8c0101;
            padding-left: 15px;
            padding-right: 15px;
        }

        .menu-item-name {
            font-weight: bold;
        }

        .menu-item-holder {
            margin-bottom: 30px;
            min-height: 80px;
        }

        .menu-item-holder > img {
            width: 105px;
            float: left;
            margin-right: 10px;
        }

        .menu-item-price {
            position: absolute;
            top: 0;
            right: 12px;
            font-family: Open Sans;
            color: #000000;
            font-size: 14px;
            font-style: normal;
            text-decoration: none;
        }

        .menu-item-name-prefix {
            font-size: 16px;
            color: #980000;
            font-weight: normal;
            font-style: normal;
            text-decoration: none;
        }

        .menu-item-name {
            font-weight: bold;
            padding-right: 30px;
            display: inline-block;
        }

        .menu-item-prepended {
            display: block;
            border-top: 1px dashed #666;
            height: 5px;
        }

        .course-quantity {
            display: inline-block;
            /*background:#aaa;
            color:#fff;*/
            color: #000;
            font-size: 18px;
            /*
            line-height:22px;
            padding:4px 8px;
            border-radius:4px;
            */
            font-weight: bold;
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

        .action-btn {
            padding-top: 5px;
        }

        .cart-holder {
            position: relative;
        }

        .category-holder {
            display: none;
        }

        .clear-cart-holder {
            margin-top: 7px;
        }

        .clear {
            clear: both;
        }

        .cart-item-name {
            display: block;
            font-weight: bold;
        }

        .cart-item-name-price {
            font-weight: normal;
        }

        .cart-item-description {
            display: block;
        }

        .cart-item-actions {
            position: absolute;
            top: 0;
            right: 0;
            width: 75px;
        }

        .cart-items-holder hr {
            margin-top: 7px;
            margin-bottom: 7px;
        }

        .cart-item-holder {
            position: relative;
            padding-right: 75px;
        }

        .item-remove-btn {
            position: absolute;
            right: 0;
            cursor: pointer;
            color: red;
            text-decoration: none;
        }

        .item-remove-btn:hover {
            text-decoration: none;
        }

        .item-minus-btn,
        .item-plus-btn {
            color: #fff;
            background-color: #5bc0de;
            min-width: 20px;
            padding: 0 5px;
            border: 1px solid #5bc0de;
            display: inline-block;
        }

        .item-minus-btn:hover,
        .item-plus-btn:hover {
            text-decoration: none;
        }

        .item-minus-btn {
            border-top-left-radius: 3px;
            border-bottom-left-radius: 3px;
        }

        .item-plus-btn {
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
        }

        .item-quantity {
            display: inline-block;
            text-align: center;
            min-width: 20px;
            padding: 0 3px;
            border-top: 1px solid #5bc0de;
            border-bottom: 1px solid #5bc0de;
        }

        .menu-item-plus-btn,
        .menu-item-minus-btn {
            padding: 4px 8px;
            margin-top: 3px;
            color: #fff;
            background-color: #5bc0de;
            min-width: 20px;
            border: 1px solid #5bc0de;
            display: inline-block;
        }

        .menu-item-minus-btn {
            border-top-left-radius: 3px;
            border-bottom-left-radius: 3px;
        }

        .menu-item-plus-btn {
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
        }

        .menu-item-quantity {
            display: inline-block;
            text-align: center;
            min-width: 20px;
            border-top: 1px solid #5bc0de;
            border-bottom: 1px solid #5bc0de;
            padding: 4px 17px;
            margin-top: 3px;
        }

        h2 {
            margin-bottom: 15px;
        }

        .action-btn-add {
            display: inline-block;
            background-color: #999;
            border-color: #777;
            border-radius: 5px;
            padding: 5px 10px;
            color: #fff;
            min-width: 100px;
            text-align: center;
            margin-top: 3px;
            text-decoration: none;
            cursor: pointer;
        }

        .action-btn a:hover {
            text-decoration: none;
        }

        .cart-loader {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(100, 100, 100, 0.7);
            color: #fff;
            border-radius: 3px;
            z-index: 99;
            text-align: center;
            font-size: 30px;

        }

        .shopping-cart__fixed {
            position: fixed;
            top: 40px;
        }

        .mobile-cart {
            background-color: #5bc0de;
            margin-top: -1px;
            position: relative;
            padding: 15px 0px;
            text-align: center;
        }

        .mobile-cart-open__button {
            position: absolute;
            right: 0;
            top: -35px;
            width: 68px;
            height: 50px;
            background-color: #d07575;
            border: none;
            outline: none;
            font-size: 18px;
            color: #fff;
            z-index: 2;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .mobile-cart-open__button:hover {
            color: #ddd;
            background-color: #7A0000;
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
            color: black;
            text-decoration: none;
            border: 1px solid black;
        }

        .categories-nav li a:hover, .categories-nav li a.active, .categories-nav li a:active {
            text-decoration: none;
            background: black;
            color: white;
        }

        .menu-action-btn {
            text-align: center;
        }

        .menu-action-btn-add {
            min-width: 250px;
        }

        .menu-suffix {
            display: block;
            margin-bottom: 40px;
            margin-top: 30px;
            border-top: 1px solid #eee;
        }

        .item-checkbox {
            display: inline-block;
            margin-right: 5px;
        }

        .item-label {
            cursor: pointer;
            font-weight: normal;
            display: block;
        }

        @media only screen and ( max-width: 1199px ) {
            .shopping-cart {
                position: absolute;
                width: 320px;
                background: #ffffff;
                z-index: 1;
                right: 0;
                top: 100px;
                min-height: 0;
                margin-top: 5px;
                padding: 0;
            }

            .shopping-cart .content-box {
                display: none;
                border: 1px solid #ddd;
                padding: 15px;
            }

            .shopping-cart__fixed {
                position: fixed;
                top: 100px;
            }

            .cart-form-holder {
                margin-bottom: 7px;
            }

            #nav-holder {
                display: none;
                padding: 0 15px;
            }

            #mobile-nav-holder {
                display: block;
            }

        }

        @media only screen and ( max-width: 768px ) {

            .preorder-menu .content-box {
                padding: 0;
                border: 0;
            }
        }

        @media only screen and ( max-width: 320px ) {
            .shopping-cart {
                width: auto;
            }
        }

    </style>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-sm-12 mboth preorder-menu">
                <div class="content-box">
                    <div class="menu-holder">

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

                        <hr>

                        <div class="menu-markup">
                            <?php
                            $config = [
                                    'items_per_row' => 2,
                                    'item_holder_classes' => ['col-md-6', 'col-sm-6', 'col-xs-12'],
                                    'language' => $menuLanguage,
                            ];
                            \App\Misc::renderActionMenu($categoriesTree, $settings, $config, $menuGroups);
                            ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-4 col-sm-12 mboth shopping-cart">

                <button type="button" class="visible-sm visible-xs visible-md mobile-cart-open__button">
                    <i class="fa fa-cutlery"></i>

                    <span class="mobile-cart__icon">
                    <i class="fa fa-angle-down"></i>
                    </span>
                </button>

                <div class="content-box">
                    <div class="cart-holder">
                        <div class="cart-loader" style="display:none;">
                            <i class="fa fa-spinner fa-pulse fa-fw"></i>
                            <span class="sr-only">Loading...</span>
                        </div>

                        <div class="heading-label hidden-sm hidden-xs calign">
                            {{ trans('online.order_cart_title') }}
                        </div>

                        <div class="clear-cart-holder">
                            <a href="javascript:;"
                               class="btn btn-danger form-control clear-cart-btn">{{ trans('online.clear_order_cart') }}</a>
                        </div>

                        <div class="cart-items-holder"></div>

                        <div class="cart-form-holder">
                            {!! Form::open(['method' => 'post', 'action' => 'Online\PreordersController@submit', 'id' => 'cart-form']) !!}
                            <div id="cart-form-inputs"></div>
                            {!! Form::submit(trans('general.submit'), ['class' => 'btn btn-success', 'id' => 'cart-submit-btn', 'style' => 'width:100%;']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop

@section('scripts')
    <script>

        function goToCategory(id) {

            $(".category-holder").hide();
            $("#nav-sel").val(id);
            $(".category-holder[id='cat-" + id + "']").fadeIn();
            $(".category-holder[id='cat-" + id + "'] .category-holder").fadeIn();
            $(".categories-nav li a").removeClass('active');
            $("#category-link-" + id).addClass('active');

        }

        function shoppingCartResizeAndScrollListener() {

            var shoppingCart = $('.shopping-cart');
            var shoppingCartContentBox = shoppingCart.find('.content-box');

            $(window).on('scroll resize', function () {

                if ($(window).width() > 1199) {

                    shoppingCartContentBox.show();

                    if ($('body').scrollTop() > $('.preorder-menu').offset().top - 40 && $(window).height() > shoppingCartContentBox.height() + 100) {
                        shoppingCartContentBox.addClass('shopping-cart__fixed');
                    } else {
                        shoppingCartContentBox.removeClass('shopping-cart__fixed').width('auto');
                    }
                    $('.shopping-cart__fixed').width($('.shopping-cart').outerWidth() - 52);

                    // on mobile resolution
                } else {
                    if ($('body').scrollTop() > $('.preorder-menu').offset().top - 200 && $(window).height() > shoppingCartContentBox.height() + 100) {
                        shoppingCart.addClass('shopping-cart__fixed');
                    } else {
                        shoppingCart.removeClass('shopping-cart__fixed');
                    }
                }
            });
        }

        function mobileShoppingCartButtonClickHandler() {
            $('.mobile-cart-open__button').click(function () {
                $('.shopping-cart').find('.content-box').slideToggle('slow');
                $('.mobile-cart__icon').find('i').toggleClass('fa-angle-down fa-angle-up');
            });
        }

        function mobileShoppingCartOpen() {
            if ($(window).width() <= 1199 && $('.content-box').is(':hidden')) {
                $('.shopping-cart').find('.content-box').slideDown('slow');
            }
        }

        function getTranslatedName(item) {

            if (!item || !item['translations']) {
                return 'Not found';
            }

            var translations = item['translations'];
            for (var key in translations) {
                if (translations[key]['language'] == '{{ $menuLanguage }}') {
                    if (!translations[key]['name'] || translations[key]['name'] == '') {
                        break;
                    }
                    return translations[key]['name'];
                }
            }
            return 'No translation found';
        }

        function getTranslatedDescription(item) {

            if (!item || !item['translations']) {
                return 'Not found';
            }

            var translations = item['translations'];
            for (var key in translations) {
                if (translations[key]['language'] == '{{ $menuLanguage }}') {
                    return translations[key]['description'];
                }
            }
            return 'No translation found';
        }

        function getShortDescription(description) {

            if (!description) {
                return '';
            }

            if (description.length <= 60) {
                return description;
            }
            else {
                return description.substr(0, 59) + '...';
            }
        }

        function updateMenu(data) {

            var menuItems = data['items'];


            $('.menu-item-holder').each(function () {

                var thisId = $(this).data('item-id');
                var buttonBlock = $(this).find('.action-btn');
                var addButton = '<a href="javascript:;" class="action-btn-add item-action-btn-add">{{ trans('online.add_button') }}</a>';

                for (var key in menuItems) {
                    var menuItem = menuItems[key];

                    if (menuItem['id'] == thisId) {

                        var numberInput = '<a href="javascript:;" class="menu-item-minus-btn" data-item-id="' + menuItem['id'] + '" >−</a>';
                        numberInput += '<span class="menu-item-quantity">' + menuItem['quantity'] + '</span>';
                        numberInput += '<a href="javascript:;" class="menu-item-plus-btn" data-item-id="' + menuItem['id'] + '" >+</a>';

                        buttonBlock.html(numberInput);

                        return true;
                    }
                }
                buttonBlock.html(addButton);
            });
            addButtonClickHandler();
            buttonsIncrementAndDecrementHandler();
        }

        function formatDecimal(number) {
            var decimalPoint = '{{ \App\Config::$decimal_point }}';
            if (decimalPoint == ',') {
                return number.replace('.', ',');
            }
            return number;
        }

        function loadCart() {

            startCartLoading();

            $.ajax({
                method: "GET",
                url: window.location.protocol + "//" + window.location.hostname + "/cart/get"
            }).done(function (data) {

                $(".cart-items-holder").html("");
                $("#cart-form-inputs").html("");

                if (!data) {
                    renderEmptyShoppingCart();
                } else if ((!data.items || data.items.length == 0)
                        && (!data.groups || data.groups.length == 0)) {
                    renderEmptyShoppingCart();
                } else {

                    var items = data['items'],
                            groups = data['groups'];


                    var itemCount = 0;
                    for (var item in items) {
                        var itemId = items[item]['id'];
                        var html = '';

                        if (itemCount > 0) {
                            html += '<hr>';
                        }

                        html += '<div class="cart-item-holder">';
                        var itemName = getTranslatedName(items[item]['item']);

                                @if ($settings['display_prices'])
                        var currency = '{{ App\Misc::printCurrency() }}';
                        var price = items[item]['item']['price'];
                        if (price && price > 0) {
                            itemName += ' <span class="cart-item-name-price">(' + currency + formatDecimal(price) + ')</span>';
                        }
                        @endif

                                html += '<span class="cart-item-name">' + itemName + '</span>';

                        var description = getTranslatedDescription(items[item]['item']);
                        var shortDescription = getShortDescription(description);

                        html += '<span class="cart-item-description" title="' + description + '">' + shortDescription + '</span>';

                        html += '<span class="cart-item-actions">';
                        html += '<a href="javascript:;" class="item-remove-btn" data-item-id="' + itemId + '" onclick="removeItem(' + itemId + ')">&times;</a>';
                        html += '<a href="javascript:;" class="item-minus-btn" data-item-id="' + itemId + '" onclick="decrementQuantity(' + itemId + ')">&minus;</a>';
                        html += '<span class="item-quantity">' + items[item]['quantity'] + '</span>';
                        html += '<a href="javascript:;" class="item-plus-btn" data-item-id="' + itemId + '" onclick="incrementQuantity(' + itemId + ')">&plus;</a>';
                        html += '</span>';
                        html += '</div>';

                        $(".cart-items-holder").append(html);
                        $("#cart-form-inputs").append('<input type="hidden" name="items[' + itemId + ']" value="' + items[item]['quantity'] + '" />');

                        itemCount++;

                    }

                    var groupCount = 0;

                    for (var groupKey in groups) {

                        var groupId = groups[groupKey]['id'];
                        var html = '';

                        if (groupCount >= 0 && itemCount > 0) {
                            html += '<hr>';
                        }

                        html += '<div class="cart-item-holder">';

                        var groupName = getTranslatedName(groups[groupKey].group);

                                @if ($settings['display_prices'])
                        var currency = '{{ App\Misc::printCurrency() }}';
                        var price = groups[groupKey]['group']['price'];
                        if (price && price > 0) {
                            groupName += ' <span class="cart-item-name-price">(' + currency + formatDecimal(price) + ')</span>';
                        }
                        @endif
                                html += '<span class="cart-item-name">' + groupName + '</span>';

                        var shortDescription = '<ul>';
                        for (var item in groups[groupKey].items) {
                            shortDescription += '<li>' + getTranslatedName(groups[groupKey].items[item]) + '</li>';
                        }
                        shortDescription += '</ul>';

                        html += '<span class="cart-item-description" title="">' + shortDescription + '</span>';

                        html += '<span class="cart-item-actions">';
                        html += '<a href="javascript:;" class="item-remove-btn" data-item-id="' + groupId + '" onclick="removeGroup(\'' + groupKey + '\')">&times;</a>';
                        html += '<a href="javascript:;" class="item-minus-btn" data-item-id="' + groupId + '" onclick="deductGroupQuantity(\'' + groupKey + '\')">&minus;</a>';
                        html += '<span class="item-quantity">' + groups[groupKey]['quantity'] + '</span>';
                        html += '<a href="javascript:;" class="item-plus-btn" data-item-id="' + groupId + '" onclick="incrementGroupQuantity(\'' + groupKey + '\')">&plus;</a>';
                        html += '</span>';
                        html += '</div>';

                        $(".cart-items-holder").append(html);

                        $("#cart-form-inputs").append('<input type="hidden" name="group_ids[\'' + groupKey + '\']" value="' + groups[groupKey]['id'] + '" />');
                        $("#cart-form-inputs").append('<input type="hidden" name="group_items[\'' + groupKey + '\']" value="' + groups[groupKey]['item_ids'] + '" />');
                        $("#cart-form-inputs").append('<input type="hidden" name="group_quantities[\'' + groupKey + '\']" value="' + groups[groupKey]['quantity'] + '" />');

                        groupCount++;
                    }

                    $("#cart-submit-btn").prop('disabled', false);
                    $("#cart-submit-btn").removeClass('disabled');

                    $(".clear-cart-btn").prop('disabled', false);
                    $(".clear-cart-btn").removeClass('disabled');

                }

                finishCartLoading();

                updateMenu(data);

            }).fail(function (xhr) {
                alert("{{ trans('online.general_error_msg') }}");
                console.log(xhr);
                finishCartLoading();
            });
        }

        function clearCart() {

            startCartLoading();

            $.ajax({
                method: "GET",
                url: window.location.protocol + "//" + window.location.hostname + "/cart/clear"
            }).done(function (data) {
                loadCart();
            }).fail(function (xhr) {
                alert("{{ trans('online.general_error_msg') }}");
                console.log(xhr);
                loadCart();
            });
        }

        function removeItem(itemId) {

            startCartLoading();

            $.ajax({
                method: "GET",
                url: window.location.protocol + "//" + window.location.hostname + "/cart/remove/items/" + itemId
            }).done(function (data) {
                loadCart();
            }).fail(function (xhr) {
                alert("{{ trans('online.general_error_msg') }}");
                console.log(xhr);
                finishCartLoading();
                loadCart();
            });
        }

        function addItem(itemId, incrementFlag) {

            startCartLoading();

            if (incrementFlag) {
                mobileShoppingCartOpen();
            }

            $.ajax({
                method: "GET",
                url: window.location.protocol + "//" + window.location.hostname + "/cart/add/items/" + itemId
            }).done(function (data) {
                loadCart();
            }).fail(function (xhr) {
                alert("{{ trans('online.general_error_msg') }}");
                console.log(xhr);
                loadCart();
            });
        }

        function buttonsIncrementAndDecrementHandler() {

            $('.menu-item-plus-btn').click(function (e) {
                e.preventDefault();
                var itemId = $(this).data('item-id');

                if ($(this).attr('disabled')) return false;

                $(this).attr('disabled', true);
                incrementQuantity(itemId);
            });

            $('.menu-item-minus-btn').click(function (e) {
                e.preventDefault();
                var itemId = $(this).data('item-id');

                if ($(this).attr('disabled')) return false;

                $(this).attr('disabled', true);
                decrementQuantity(itemId);
            });

        }

        function incrementQuantity(itemId) {
            addItem(itemId, false);
        }

        function decrementQuantity(itemId) {
            startCartLoading();

            $.ajax({
                method: "GET",
                url: window.location.protocol + "//" + window.location.hostname + "/cart/deduct/items/" + itemId
            }).done(function (data) {
                loadCart();
            }).fail(function (xhr) {
                alert("{{ trans('online.general_error_msg') }}");
                console.log(xhr);
                loadCart();
            });
        }

        function startCartLoading() {
            $(".cart-loader").css('padding-top', $('.shopping-cart').height() / 2.4).fadeIn();
        }

        function finishCartLoading() {
            $(".cart-loader").fadeOut();
        }


        function addButtonClickHandler() {

            $(".item-action-btn-add").click(function (e) {

                e.preventDefault();
                var itemId = $(this).parent().parent().attr('data-item-id');
                if ($(this).attr('disabled')) return false;
                $(this).attr('disabled', true);
                addItem(itemId, true);
            });
        }

        function addGroupEventHandler() {

            $(".menu-action-btn-add").click(function (e) {

                e.preventDefault();
                var categoryHolder = $(this).closest('.category-holder');
                var $categoryId = categoryHolder.data('category-id');
                var $errors = false;

                categoryHolder.find('.course-content').each(function () {
                    var $courseQuantity = $(this).data('course-quantity');
                    var $checkedInputs = $(this).find('input:checked');

                    if ($checkedInputs.length != $courseQuantity) $errors = true
                });

                if (!$errors) {
                    var $selectedItems = [];

                    categoryHolder.find('input:checked').each(function (index) {
                        $selectedItems[index] = $(this).data('item-id');
                    });

                    addGroup($selectedItems, $categoryId);

                } else {
                    alert("{{ trans('online.invalid_selected_items_error_msg') }}");
                }

            });
        }

        function incrementGroupQuantity(groupKey) {
            startCartLoading();

            $.ajax({
                method: "GET",
                url: window.location.protocol + "//" + window.location.hostname + "/cart/increment/groups/" + groupKey
            }).done(function (data) {
                loadCart();
            }).fail(function (xhr) {
                alert("{{ trans('online.general_error_msg') }}");
                console.log(xhr);
                loadCart();
            });
        }

        function deductGroupQuantity(groupKey) {
            startCartLoading();

            $.ajax({
                method: "GET",
                url: window.location.protocol + "//" + window.location.hostname + "/cart/deduct/groups/" + groupKey
            }).done(function (data) {
                loadCart();
            }).fail(function (xhr) {
                alert("{{ trans('online.general_error_msg') }}");
                console.log(xhr);
                loadCart();
            });
        }

        function removeGroup(groupKey) {
            startCartLoading();

            $.ajax({
                method: "GET",
                url: window.location.protocol + "//" + window.location.hostname + "/cart/remove/groups/" + groupKey
            }).done(function (data) {
                loadCart();
            }).fail(function (xhr) {
                alert("{{ trans('online.general_error_msg') }}");
                console.log(xhr);
                loadCart();
            });
        }

        function addGroup(selectedItemIds, categoryId) {

            startCartLoading();

            mobileShoppingCartOpen();

            $.ajax({
                method: "POST",
                url: window.location.protocol + "//" + window.location.hostname + "/cart/add/groups/" + categoryId,
                data: {'items': selectedItemIds}
            }).done(function (data) {
                loadCart();
            }).fail(function (xhr) {
                alert("{{ trans('online.general_error_msg') }}");
                console.log(xhr);
                loadCart();
            });
        }

        function renderEmptyShoppingCart() {
            $(".cart-items-holder").html("{{ trans('online.no_items_in_order') }}");
            $("#cart-submit-btn").prop('disabled', true);
            $("#cart-submit-btn").addClass('disabled');

            $(".clear-cart-btn").prop('disabled', true);
            $(".clear-cart-btn").addClass('disabled');
        }

        function setBeforeUnload() {

            window.onbeforeunload = function () {
                return true;
            };
        }

        function unsetBeforeUnload() {
            window.onbeforeunload = null;
        }

        $(function () {

            loadCart();

            shoppingCartResizeAndScrollListener();

            addGroupEventHandler();

            mobileShoppingCartButtonClickHandler();

            buttonsIncrementAndDecrementHandler();

            $("#category-sel").change(function () {
                var categoryId = $(this).val();
                loadCategoryItems(categoryId);
            });

            $(".categories-nav li a").first().click();

            $("#nav-sel").change(function () {
                var id = $(this).val();
                goToCategory(id);
            });

            $(".clear-cart-btn").click(function () {
                clearCart();
            });


            $('.course-content').each(function () {
                var $inputs = $(this).find('input');
                var $courseQuantity = $(this).data('course-quantity');
                if ($inputs.length == $courseQuantity) {
                    $inputs.prop('checked', true);
                    $inputs.attr('disabled', 'true');
                }
            });


            $('.item-checkbox input').change(function () {
                var $course = $(this).closest('.course-content');
                var $courseQuantity = $course.data('course-quantity');

                if ($course.find('input:checked').length > $courseQuantity) {
                    $course.find('input').prop('checked', false);
                    $(this).prop('checked', true);
                }
            });

            setBeforeUnload();

            $("#cart-form").submit(function () {
                unsetBeforeUnload();
            });

        });
    </script>
@stop
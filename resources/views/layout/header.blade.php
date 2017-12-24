<header class="header">
    <div class="header-logo">
        <a href="{{ url('/') }}" class="header-logo__link">
            {{ \App\Config::$restaurant_name }}
        </a>
    </div>

    <div class="header-menu">

        @if (\App\Config::$has_reception)
            <div class="header-menu__item {{ (strpos(url()->current(), 'reception') !== false || !Request::path()) ? 'active' : ''}}">
                <a href="{{ url('reception') }}" class="header-menu__item-link">
                    {{ trans('reception.reception') }}
                </a>
            </div>
        @endif

        @if (\App\Config::$has_kitchen)

            <div class="header-menu__item {{ strpos(url()->current(), 'kitchen') !== false ? 'active' : ''}}">
                <a href="{{ url('kitchen') }}" class="header-menu__item-link">
                    {{ trans('kitchen.kitchen') }}
                </a>
            </div>
        @endif
        @if (\App\Config::$has_restaurant)
            <div class="header-menu__item {{ strpos(url()->current(), 'restaurant') !== false ? 'active' : ''}}">
                <a href="{{ url('restaurant') }}" class="header-menu__item-link">
                    {{ trans('restaurant.restaurant') }}
                </a>
            </div>
        @endif
        @if (\App\Config::$has_clients)
            <div class="header-menu__item {{ strpos(url()->current(), '/crm') !== false ? 'active' : ''}}">
                <a href="{{ url('crm') }}" class="header-menu__item-link">
                    {{ trans('crm.clients') }}
                </a>
            </div>
        @endif
        @if (\App\Config::$has_analytics)
            <div class="header-menu__item {{ (strpos(url()->current(), 'analytics') !== false) || (isset($requestedUrl) && strpos($requestedUrl, 'analytics')) ? 'active' : ''}}">
                <a href="{{ url('analytics') }}" class="header-menu__item-link">
                    {{ trans('analytics.analytics') }}
                </a>
            </div>
        @endif

        @if (\App\Config::$has_admin)
            <div class="header-menu__item {{ (strpos(url()->current(), 'admin') !== false) || (isset($requestedUrl) && strpos($requestedUrl, 'admin')) ? 'active' : ''}}">
                <a href="{{ url('admin') }}" class="header-menu__item-link">
                    {{ trans('admin.admin') }}
                </a>
            </div>
        @endif
    </div>

    @yield('header-print')

    <div class="header-profile">

        <button class="header-profile__button js-header-profile__button">
            <a href="javascript:" class="js-logout__link header-profile__button-logout__link">
                <svg class="header-profile__button-logout__link-icon">
                    <use xlink:href="#icon-logout"></use>
                </svg>
            </a>
        </button>
        <form class="js-logout__form" action="{{ action('Auth\LoginController@logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

        <div class="header-profile__menu js-header-profile__menu">
            {{--<div class="header-profile__menu-item">--}}
                {{--<a href="javascript:" class="header-profile__menu-item--link">--}}
                    {{--<svg class="header-profile__menu-item--icon">--}}
                        {{--<use xlink:href="#icon-user"></use>--}}
                    {{--</svg>--}}
                    {{--<span> {{ trans('auth.profile') }} </span>--}}
                {{--</a>--}}
            {{--</div>--}}

            {{--<div class="header-profile__menu-item">--}}
                {{--<a href="javascript:" class="header-profile__menu-item--link">--}}
                    {{--<svg class="header-profile__menu-item--icon">--}}
                        {{--<use xlink:href="#icon-settings"></use>--}}
                    {{--</svg>--}}
                    {{--<span> {{ trans('auth.settings') }} </span>--}}
                {{--</a>--}}
            {{--</div>--}}


            {{--<div class="header-profile__menu-item">--}}
                {{--<a href="javascript:" class="js-logout__link header-profile__menu-item--link">--}}
                    {{--<svg class="header-profile__menu-item--icon">--}}
                        {{--<use xlink:href="#icon-logout"></use>--}}
                    {{--</svg>--}}
                    {{--{{ trans('auth.logout') }}--}}
                {{--</a>--}}
                {{--<form class="js-logout__form" action="{{ action('Auth\LoginController@logout') }}" method="POST" style="display: none;">--}}
                    {{--{{ csrf_field() }}--}}
                {{--</form>--}}
            {{--</div>--}}
        </div>
    </div>

</header>

<header class="header">
    <div class="header-logo">
        <a href="{{ url('/') }}" class="header-logo__link">
            VITisch
        </a>
    </div>

    <div class="header-menu">

        <div class="header-menu__item {{ (strpos(url()->current(), 'admin') !== false) || (isset($requestedUrl) && strpos($requestedUrl, 'admin')) ? 'active' : ''}}">
            <a href="{{ url('admin') }}" class="header-menu__item-link">
                {{ trans('admin.admin') }}
            </a>
        </div>
    </div>

    @yield('header-print')

    <div class="header-profile">

        <button class="header-profile__button js-header-profile__button">
            <svg class="header-profile__button--icon-user">
                <use xlink:href="#icon-user"></use>
            </svg>
            <svg class="header-profile__button--icon-arrow">
                <use xlink:href="#icon-arrow-down"></use>
            </svg>
        </button>

        <div class="header-profile__menu js-header-profile__menu">

            <div class="header-profile__menu-item">
                <a href="javascript:" class="js-logout__link header-profile__menu-item--link">
                    <svg class="header-profile__menu-item--icon">
                        <use xlink:href="#icon-logout"></use>
                    </svg>
                    {{ trans('auth.logout') }}
                </a>
                <form class="js-logout__form" action="{{ action('Auth\LoginController@logout') }}" method="POST"
                      style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>

</header>

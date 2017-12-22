<aside class="sidebar">
    <nav class="sidebar-nav">
        <ul class="sidebar-nav__menu">

            @yield('nav')

        </ul>
    </nav>

    <div class="sidebar-toggle">
        <button class="sidebar-toggle__button js-sidebar-toggle__button">
            <svg class="sidebar-toggle__button--icon">
                <use xlink:href="#icon-bars"></use>
            </svg>
        </button>
    </div>


    <div class="sidebar-content">
        <div class="sidebar-calendar js-sidebar-calendar"></div>
    </div>

    <div class="sidebar-footer">
        <div class="sidebar-footer__logo">
            <svg class="sidebar-footer__logo--icon">
                <use xlink:href="#icon-logo-black"></use>
            </svg>
        </div>

        <div class="sidebar-footer__version">
            V {{ Config::get('app.version') }}
        </div>

        <div class="sidebar-footer__copyright">
            &copy; VITisch 2016 - {{ date("Y") }}
        </div>

        <div class="sidebar-footer__contact">
            <a href="http://vitisch.de/terms" class="sidebar-footer__contact--link" target="_blank">
                {{ trans('general.terms_of_use') }}
            </a>

            <a href="{{ url('/contact') }}" class="sidebar-footer__contact--link">
                {{ trans('general.contact_us') }}
            </a>
        </div>
    </div>
</aside>

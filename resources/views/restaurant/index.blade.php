@extends('restaurant.master')

@section('nav')

    <li class="sidebar-nav__menu-item {{ in_array($pageName, ['sections', 'table-plans', 'table-plan-schedule']) ? 'active current' : ''}}">
        <a href="javascript:" class="sidebar-nav__menu-item--link">
            Tables Management

            <svg class="sidebar-nav__menu-item--icon">
                <use xlink:href="#icon-arrow-down"></use>
            </svg>
        </a>

        <ul class="sidebar-nav__submenu">
            <li class="sidebar-nav__submenu-item {{ $pageName == 'sections' ? 'current' : '' }}" data-page="sections">
                <a href="{{ action('SectionsController@index') }}" class="sidebar-nav__submenu-item--link">
                    {{ trans('restaurant.restaurant_sections') }}
                </a>
            </li>

            <li class="sidebar-nav__submenu-item {{ $pageName == 'table-plans' ? 'current' : ''}}" data-page="table-plans">
                <a href="{{ action('TablePlansController@index') }}" class="sidebar-nav__submenu-item--link">
                    {{ trans('restaurant.table_plans') }}
                </a>
            </li>

            <li class="sidebar-nav__submenu-item {{ $pageName == 'table-plan-schedule' ? 'current' : '' }}" data-page="table-plan-schedule">
                <a href="{{ action('TablePlansController@tablePlanSchedule') }}"
                   class="sidebar-nav__submenu-item--link">
                    {{ trans('restaurant.table_plan_schedule') }}
                </a>
            </li>

        </ul>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>

    <li class="sidebar-nav__menu-item {{ in_array($pageName, ['categories', 'menu-items', 'menus', 'menu-groups']) ? 'active current' : ''}}">
        <a href="javascript:" class="sidebar-nav__menu-item--link">
            Menu Management
            <svg class="sidebar-nav__menu-item--icon">
                <use xlink:href="#icon-arrow-down"></use>
            </svg>
        </a>

        <ul class="sidebar-nav__submenu">

            <li class="sidebar-nav__submenu-item {{ $pageName == 'categories' ? 'current' : '' }}" data-page="categories">
                <a href="{{ action('MenuCategoriesController@index') }}" class="sidebar-nav__submenu-item--link">
                    {{ trans('restaurant.menu_categories') }}
                </a>
            </li>

            <li class="sidebar-nav__submenu-item {{ $pageName == 'menu-items' ? 'current' : '' }}" data-page="menu-items">
                <a href="{{ action('MenuItemsController@index') }}" class="sidebar-nav__submenu-item--link">
                    {{ trans('restaurant.menu_items') }}
                </a>
            </li>

            <li class="sidebar-nav__submenu-item {{ $pageName == 'menus' ? 'current' : ''}}" data-page="menus">
                <a href="{{ action('CustomMenusController@index') }}"
                   class="sidebar-nav__submenu-item--link">
                    {{ trans('restaurant.custom_menus') }}
                </a>
            </li>

            <li class="sidebar-nav__submenu-item {{ $pageName == 'menu-groups' ? 'current' : '' }}" data-page="menu-groups">
                <a href="{{ action('MenuGroupsController@index') }}" class="sidebar-nav__submenu-item--link">
                    {{ trans('restaurant.menus') }}
                </a>
            </li>
        </ul>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>

    <li class="sidebar-nav__menu-item">
        <a href="{{ action('OnlineMenuController@editor') }}" target="_blank" class="sidebar-nav__menu-item--link">
            {{ trans('restaurant.online_menu') }}
        </a>
    </li>

@stop
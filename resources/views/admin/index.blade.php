@extends('admin.master')

@section('nav')

    <li class="sidebar-nav__menu-item {{ $pageName == 'admin-basic' ? 'current active' : '' }}" data-page="admin-basic">
        <a href="{{ action('ConfigController@basic') }}" class="sidebar-nav__menu-item--link">
            Information
        </a>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>

    <li class="sidebar-nav__menu-item {{ $pageName == 'reservation-hours' ? 'current active' : '' }}" data-page="reservation-hours">
        <a href="{{ action('ConfigController@reservationHours') }}" class="sidebar-nav__menu-item--link">
            {{ trans('admin.reservation_hours') }}
        </a>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>


    <li class="sidebar-nav__menu-item {{ $pageName == 'online-settings' ? 'current active' : '' }}" data-page="online-settings">
        <a href="{{ action('ConfigController@online') }}" class="sidebar-nav__menu-item--link">
            {{ trans('admin.online_reservation_settings') }}
        </a>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>

    <li class="sidebar-nav__menu-item {{ $pageName == 'online-languages' ? 'current active' : '' }}" data-page="online-languages">
        <a href="{{ action('ConfigController@onlineLanguages') }}" class="sidebar-nav__menu-item--link">
            Menu Language
        </a>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>


    @if (\App\Config::$has_kitchen)

        <li class="sidebar-nav__menu-item {{ $pageName == 'preorders' ? 'current active' : '' }}" data-page="preorders">
            <a href="{{ action('ConfigController@preorders') }}" class="sidebar-nav__menu-item--link">
                {{ trans('admin.preorder_settings') }}
            </a>

            <svg class="sidebar-nav__menu-item--tail">
                <use xlink:href="#icon-tail"></use>
            </svg>
        </li>

    @endif

    <li class="sidebar-nav__menu-item {{ $pageName == 'change-password' ? 'current active' : '' }}" data-page="change-password">
        <a href="{{ action('UsersController@editPassword') }}" class="sidebar-nav__menu-item--link">
            Password
        </a>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>
@stop

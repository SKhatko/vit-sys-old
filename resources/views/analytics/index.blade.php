@extends('analytics.master')

@section('nav')

    <li class="sidebar-nav__menu-item {{ $pageName == 'reservations_daily_stats' ? 'current active' : '' }}"
        data-page="reservations_daily_stats">
        <a href="{{ action('AnalyticsController@reservationsDailyStats') }}" class="sidebar-nav__menu-item--link">
            {{ trans('analytics.reservations_daily_stats') }}
        </a>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>

    <li class="sidebar-nav__menu-item {{ $pageName == 'reservations_monthly_stats' ? 'current active' : '' }}"
        data-page="reservations_monthly_stats">
        <a href="{{ action('AnalyticsController@reservationsMonthlyStats') }}" class="sidebar-nav__menu-item--link">
            {{ trans('analytics.reservations_monthly_stats') }}
        </a>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>

    <li class="sidebar-nav__menu-item {{ $pageName == 'reservation_statuses_report' ? 'current active' : '' }}"
        data-page="reservation_statuses_report">
        <a href="{{ action('AnalyticsController@reservationStatuses') }}" class="sidebar-nav__menu-item--link">
            {{ trans('analytics.reservation_statuses_report') }}
        </a>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>

    <li class="sidebar-nav__menu-item {{ $pageName == 'reservations_ref_report' ? 'current active' : '' }}"
        data-page="reservations_ref_report">
        <a href="{{ action('AnalyticsController@reservationsRefReport') }}" class="sidebar-nav__menu-item--link">
            {{ trans('analytics.reservations_ref_report') }}
        </a>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>

@stop

<!-- TODO remove head styles -->

@section('head')
    <link href="{{ asset('/css/admin.css?v=3.2') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
@stop
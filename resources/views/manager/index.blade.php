@extends('manager.master')

@section('nav')

    <li class="sidebar-nav__menu-item current active" data-page="manager">
        <a href="{{ action('TenantsController@index') }}" class="sidebar-nav__menu-item--link">
            {{ trans('manager.tenants') }}
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

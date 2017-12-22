@extends('crm.master')

@section('header-print')
    @include('components.print_button')
@stop

@section('nav')

    <li class="sidebar-nav__menu-item {{ $pageName == 'clients' ? 'current active' : '' }}" data-page="clients" >
        <a href="{{ action('ClientsController@index') }}" class="sidebar-nav__menu-item--link">
            {{ trans('crm.clients') }}
        </a>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>

    <li class="sidebar-nav__menu-item {{ $pageName == 'companies' ? 'current active' : '' }}" data-page="companies" >
        <a href="{{ action('CompaniesController@index') }}" class="sidebar-nav__menu-item--link">
            {{ trans('crm.companies') }}
        </a>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>

@stop
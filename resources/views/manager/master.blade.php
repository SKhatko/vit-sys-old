@extends('app')

@section('layout')

    <!-- Include header -->
    @include('layout/manager_header')

    <main class="main">

        <!-- Include sidebar -->
    @include('layout/sidebar')

    <!-- Include content -->
        <section class="content">
            @yield('content')
        </section>

    </main>

@stop

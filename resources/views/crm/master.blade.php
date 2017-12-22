@extends('app')

@section('layout')

    <!-- Include header -->
    @include('layout/header')

    <main class="main">

        <!-- Include sidebar -->
    @include('layout/sidebar')

    <!-- Include content -->
        <section class="content">
            @yield('content')
        </section>

    </main>

@stop

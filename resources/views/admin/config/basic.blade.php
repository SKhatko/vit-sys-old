@extends('admin.index')


@section('content')

@include('admin.config._basic_form')

@stop

@section('script')
    <script>
        new Admin()
    </script>
@stop
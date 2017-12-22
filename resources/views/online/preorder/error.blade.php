@extends('online.preorder.master')

@section('content')

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger mboth">
                        {{ trans('online.' . $errorType . '_preorder_error_msg') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
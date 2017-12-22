@extends('online.preorder.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mtop">
                {!! Form::open(['method' => 'DELETE', 'action' => 'Online\PreordersController@destroy']) !!}
                <div class="form-group">
                    <p class="alert alert-warning">{!! trans('online.delete_preorder_confirmation_msg_with_name', ['name' => '<strong>'.$preorder->name.'</strong>']) !!}</p>
                </div>

                <div class="form-group">
                    {!! Form::submit(trans('online.cancel_order'), ['class' => 'btn btn-danger']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
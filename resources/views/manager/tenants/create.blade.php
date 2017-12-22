@extends('manager.index')

@section('content')

    <div class="col-md-12">
    
        {!! Form::open(['method' => 'post', 'action' => 'TenantsController@store']) !!}
        <div class="form-group">
            {!! Form::label('name', 'Restaurant Name:') !!}
            {!! Form::text('name', NULL, ['class' => 'form-control', 'required']) !!}
        </div>
        
        <div class="form-group">
            {!! Form::label('domain', 'Restaurant website domain:') !!}
            {!! Form::text('domain', NULL, ['class' => 'form-control', 'required']) !!}
        </div>
        
        <div class="form-group">
            {!! Form::label('subdomain', 'VITisch Subdomain:') !!}
            {!! Form::text('subdomain', NULL, ['class' => 'form-control', 'required']) !!}
        </div>
        
        <div class="form-group">
            {!! Form::label('key_name', 'System Name (English Letters, numbers & hyphons only):') !!}
            {!! Form::text('key_name', NULL, ['class' => 'form-control', 'required']) !!}
        </div>
        
        <div class="form-group">
            {!! Form::submit('Create', ['class' => 'btn btn-primary', 'id' => 'submit-btn']) !!}
        </div>
        {!! Form::close() !!}
        
    </div>
@stop

@section('script')
    <script>
    $(function() {
        $("form").submit(function() {
            $("#submit-btn").addClass('disabled');
            $("#submit-btn").prop('disabled', true);
            $("#general-loader").show();
        });
    });
    </script>
@stop
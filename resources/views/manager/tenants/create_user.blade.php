@extends('manager.index')

@section('content')

    <div class="col-md-12 pnone">
        {!! Form::model($user = new \App\User, ['action' => ['TenantsController@storeTenantUser', $tenant->id], 'method' => 'POST']) !!}
            @include('manager.tenants._user_form', ['submitButtonText' => trans('manager.create')])
        {!! Form::close() !!}
    </div>

@stop
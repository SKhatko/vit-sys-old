@extends('manager.index')

@section('content')

    <div class="col-md-12 pnone">
        {!! Form::model($user, ['action' => ['TenantsController@updateTenantUser', $tenant->id, $user->id], 'method' => 'POST']) !!}
        @include('manager.tenants._user_form', ['submitButtonText' => trans('manager.update')])
        {!! Form::close() !!}
    </div>

@stop
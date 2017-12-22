@extends('manager.index')

@section('content')

    <div class="col-md-12">

        <div class="form-group">
            <a href="{{ action('TenantsController@create') }}"
               class="btn btn-primary">{{ trans('manager.create_tenant') }}</a>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>{{ trans('manager.name') }}</th>
                <th>{{ trans('manager.subdomain') }}</th>
                <th>{{ trans('manager.website_or_domain') }}</th>
                <th>{{ trans('manager.active') }}</th>
                <th>{{ trans('manager.actions') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($tenants as $tenant)
                <tr>
                    <td>{{ $tenant->name }}</td>
                    <td>{{ $tenant->subdomain }}</td>
                    <td>{{ $tenant->domain }}</td>
                    <td>
                        @if ($tenant->active)
                            <span color="green">{{ trans('manager.active') }}</span>
                        @else
                            <span color="red">{{ trans('manager.inactive') }}</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ action('TenantsController@show', ['id' => $tenant->id]) }}"
                           class="small-btn btn-warning">View data</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@stop
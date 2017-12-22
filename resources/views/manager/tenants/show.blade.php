@extends('manager.index')

@section('content')
    <div class="nav-section">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li class="{{ $tab == 'account' ? 'active' : '' }}"><a
                            href="{{ action('TenantsController@show', ['id' => $tenant->id, 'tab' => 'account']) }}">Account
                        Info</a></li>

                <li class="{{ $tab == 'config' ? 'active' : '' }}"><a
                            href="{{ action('TenantsController@show', ['id' => $tenant->id, 'tab' => 'config']) }}">Tenant
                        Configurations</a></li>

                <li class="{{ $tab == 'interfaces' ? 'active' : '' }}"><a
                            href="{{ action('TenantsController@show', ['id' => $tenant->id, 'tab' => 'interfaces']) }}">Interfaces</a>
                </li>

                <li class="{{ $tab == 'pin' ? 'active' : '' }}"><a
                            href="{{ action('TenantsController@show', ['id' => $tenant->id, 'tab' => 'pin']) }}">PIN</a></li>

                <li class="{{ $tab == 'users' ? 'active' : '' }}"><a
                            href="{{ action('TenantsController@show', ['id' => $tenant->id, 'tab' => 'users']) }}">Users</a>
                </li>
                <li class="{{ $tab == 'reception-stats' ? 'active' : '' }}"><a
                            href="{{ action('TenantsController@show', ['id' => $tenant->id, 'tab' => 'reception-stats']) }}">Reservation
                        Stats</a></li>
                <li class="{{ $tab == 'menu-stats' ? 'active' : '' }}"><a
                            href="{{ action('TenantsController@show', ['id' => $tenant->id, 'tab' => 'menu-stats']) }}">Menu
                        Stats</a></li>
            </ul>

        </div>
        <div class="clear"></div>
    </div>

    <div class="nav-content-section mboth">
        <div class="col-md-12">
            @if ($tab == 'account')

                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('name',	trans('manager.name')) !!}<br>
                        {{ $tenant->name }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('domain', trans('manager.website_or_domain')) !!}<br>
                        {{ $tenant->domain }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('subdomain', trans('manager.subdomain')) !!}<br>
                        {{ $tenant->subdomain }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('status', trans('manager.status')) !!}<br>
                        {!! $tenant->active ? '<font color="green">'.trans('manager.active').'</font>' : '<font color="red">'.trans('manager.inactive').'</font>' !!}
                    </div>
                </div>

            @elseif ($tab == 'users')

                <div class="col-md-12">
                    <h3>Users</h3>
                    
                    <div class="form-group">
                       <a href="{{ action('TenantsController@createTenantUser', [$tenant->id]) }}" class="btn btn-primary">Create new user</a>
                    </div>
                    
                    <table class="table table-border table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Created at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ date("Y-m-d", strtotime($user->created_at)) }}</td>
                                <td>
                                    <a href="{{ action('TenantsController@editTenantUser', [$tenant->id, $user->id]) }}"
                                       class="small-btn btn-primary">{{ trans('manager.edit') }}</a>
                                    <a href="javascript:;" class="small-btn btn-warning"
                                       onclick="resetPassword('{{ json_encode($user) }}');">{{ trans('manager.reset_password') }}</a>
                                    <a href="javascript:;" class="small-btn btn-danger"
                                       onclick="deleteUser('{{ json_encode($user) }}');">delete</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            @elseif ($tab == 'config')

                {!! Form::open(['method' => 'post', 'action' => ['TenantsController@updateTenantConfig', $tenant->id]]) !!}

                @include('admin.config._basic_form')

                {!! Form::close() !!}

            @elseif ($tab == 'interfaces')

                @include('manager.tenants._interfaces_tab')

            @elseif ($tab == 'pin')

                @include('manager.tenants._pin_tab')

            @elseif ($tab == 'reception-stats')

                <div class="col-md-6">
                    <h3>Monthly Stats</h3>
                    <table class="table table-border table-striped" style="max-width:500px;">

                        <tr>
                            <th>Month</th>
                            <th>Reservations</th>
                            <th>Persons</th>
                        </tr>

                        @foreach ($reservationsData['monthly_stats'] as $row)
                            <tr>
                                <td>{{ date("Y-m", strtotime($row->MONTH)) }}</td>
                                <td>{{ $row->COUNT }}</td>
                                <td>{{ $row->PERSONS }}</td>
                            </tr>
                        @endforeach

                    </table>
                </div>

                <div class="col-md-6">
                    <h3>Last 15 days</h3>
                    <table class="table table-border table-striped" style="max-width:500px;">

                        <tr>
                            <th>Date</th>
                            <th>Reservations</th>
                            <th>Persons</th>
                        </tr>

                        @foreach ($reservationsData['daily_stats'] as $row)
                            <tr>
                                <td>{{ $row->DAY }}</td>
                                <td>{{ $row->COUNT }}</td>
                                <td>{{ $row->PERSONS }}</td>
                            </tr>
                        @endforeach

                    </table>
                </div>

            @elseif ($tab == 'menu-stats')

                <div class="col-md-6">
                    <h3>Monthly Stats</h3>
                    <table class="table table-border table-striped" style="max-width:500px;">

                        <tr>
                            <th>Month</th>
                            <th>Visitors</th>
                            <th>Views</th>
                        </tr>

                        @foreach ($menuData['monthly_stats'] as $row)
                            <tr>
                                <td>{{ date("Y-m", strtotime($row->MONTH)) }}</td>
                                <td>{{ $row->VISITORS }}</td>
                                <td>{{ $row->VIEWS }}</td>
                            </tr>
                        @endforeach

                    </table>
                </div>

                <div class="col-md-6">
                    <h3>Last 15 days</h3>
                    <table class="table table-border table-striped" style="max-width:500px;">

                        <tr>
                            <th>Date</th>
                            <th>Visitors</th>
                            <th>Views</th>
                        </tr>

                        @foreach ($menuData['daily_stats'] as $row)
                            <tr>
                                <td>{{ $row->date }}</td>
                                <td>{{ $row->visitors }}</td>
                                <td>{{ $row->views }}</td>
                            </tr>
                        @endforeach

                    </table>
                </div>

            @endif
        </div>
    </div>

    @include('partials.modal_top', ['modalId' => 'reset-password-dialog'])
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>{{ trans('manager.reset_user_password') }}</h3>
    </div>

    <div class="modal-body">

        {!! Form::open(['method' => 'post', 'action' => 'TenantsController@index', 'id' => 'reset-password-form']) !!}

        <p>
            {{ trans('manager.reset_user_password_confirmation_msg') }}
        </p>

        <p id="reset-password-email">

        </p>

        <div class="clear"></div>
    </div>

    <div class="modal-footer">
        {!! Form::submit(trans('manager.confirm'), ['class' => 'btn btn-primary', 'id' => 'reset-password-confirm-btn']) !!}

        <a href="javascript:;" class="btn" data-dismiss="modal">{{ trans('manager.cancel') }}</a>

        {!! Form::close() !!}
    </div>

    @include('partials.modal_bottom')
    
    @include('partials.modal_top', ['modalId' => 'delete-user-dialog'])
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>Delete user</h3>
    </div>

    <div class="modal-body">

        {!! Form::open(['method' => 'delete', 'action' => ['TenantsController@destroyTenantUser', $tenant->id], 'id' => 'delete-user-form']) !!}

        <p>
            Are you sure you want to delete the user with email
        </p>

        <p id="delete-user-email">

        </p>

        <div class="clear"></div>
    </div>

    <div class="modal-footer">
        {!! Form::submit(trans('manager.confirm'), ['class' => 'btn btn-danger', 'id' => 'delete-user-confirm-btn']) !!}

        <a href="javascript:;" class="btn" data-dismiss="modal">{{ trans('manager.cancel') }}</a>

        {!! Form::close() !!}
    </div>

    @include('partials.modal_bottom')
@stop

@section('script')

    <script>
        function resetPassword(userObj) {
            var user = JSON.parse(userObj);
            $("#reset-password-form").attr('action', '{{ action('TenantsController@index') }}/{{ $tenant->id }}/users/' + user['id'] + '/reset-password');
            $("#reset-password-email").html('<i><strong>' + user['email'] + '</strong></i>');
            $("#reset-password-dialog").modal('show');
        }
        
        function deleteUser(userObj) {
            var user = JSON.parse(userObj);
            $("#delete-user-form").attr('action', '{{ action('TenantsController@destroyTenantUser', [$tenant->id]) }}/' + user['id'] );
            $("#delete-user-email").html('<i><strong>' + user['email'] + '</strong></i>');
            $("#delete-user-dialog").modal('show');
        }
    </script>
@stop
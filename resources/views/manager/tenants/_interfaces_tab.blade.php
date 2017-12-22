{!! Form::open(['method' => 'post', 'action' => ['TenantsController@updateInterfaces', $tenant->id]]) !!}

<div class="form-group">
    <input type="checkbox" name="reception" value="1" {{ $tenant->reception_enabled ? 'checked' : '' }}> Reception<br>
    <input type="checkbox" name="kitchen" value="1" {{ $tenant->kitchen_enabled ? 'checked' : '' }}> Kitchen<br>
    <input type="checkbox" name="restaurant" value="1" {{ $tenant->restaurant_enabled ? 'checked' : '' }}>
    Restaurant<br>
    <input type="checkbox" name="clients" value="1" {{ $tenant->clients_enabled ? 'checked' : '' }}> Clients<br>
    <input type="checkbox" name="analytics" value="1" {{ $tenant->analytics_enabled ? 'checked' : '' }}> Analytics<br>
    <input type="checkbox" name="admin" value="1" {{ $tenant->admin_enabled ? 'checked' : '' }}> Admin<br>
</div>

<div class="form-group">
    {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}
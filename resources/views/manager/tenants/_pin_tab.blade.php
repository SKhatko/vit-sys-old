<div class="form-group">
    @if (\App\Config::$pin)
        Pin Code: <strong>{{ \App\Config::$pin }}</strong>
    @else
        Pin Code <strong><span color="red">Not Set</span></strong>
    @endif
</div>

<div class="form-group">
    @if (!\App\Config::$pin)
        <a href="{{ action('TenantsController@enablePin', [$tenant->id]) }}" class="btn btn-primary">Enable Pin Code</a>
    @else
        <a href="{{ action('TenantsController@resetPin', [$tenant->id]) }}" class="btn btn-warning">Reset Pin Code</a>
        <a href="{{ action('TenantsController@disablePin', [$tenant->id]) }}" class="btn btn-danger">Disable Pin
            Code</a>
    @endif
</div>
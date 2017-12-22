<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>

<body>
<p>
    {{ trans('manager.user_greeting', ['name' => $user->name]) }},
</p>

<p>
    {!! trans('manager.new_password_is', ['password' => $newPassword]) !!}
</p>

<p>
    {{ trans('manager.you_can_change_password') }}.
</p>

<p>
    {{ trans('manager.this_is_automatic_email') }}:<br>
    {{ Config::get('app.vitisch_email') }}<br>
    {{ Config::get('app.vitisch_phone') }}
</p>

<p style="color:#a62222">
    <strong>
        <i>
            {!! trans('online.vitisch_signature') !!}
        </i>
    </strong>
</p>
</body>
</html>
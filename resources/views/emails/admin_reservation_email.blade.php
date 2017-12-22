<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>

<body>
<p>
    {{ trans('online.new_online_reservation_was_placed') }}:
</p>

<p>
    <strong><span style="color:#a62222">{{ trans('reception.reservation_info') }}</span></strong>:<br>
    {{ trans('online.reference_id') }}: <strong><i>{{ $referenceId }}</i></strong><br>
    {{ trans('online.date_and_time') }}: <strong><i>{{ $time }}</i></strong><br>
    {{ trans('general.persons_num') }}: <strong><i>{{ $personsNum }}</i></strong><br>
    {{ trans('reception.ref') }}: <strong><i>{{ $ref }}</i></strong><br>

    @if ($notes)
        {{ trans('online.additional_notes') }}: {{ $notes }}
    @endif
</p>

<p>
    <strong><span style="color:#a62222">{{ trans('online.client_details') }}</span></strong>:<br>
    {{ trans('general.name') }}: <strong><i>{{ $name }}</i></strong>
    <br>
    {{ trans('auth.email') }}: <strong><i>{{ $email }}</i></strong>
    <br>
    {{ trans('general.mobile') }}: <strong><i>{{ $mobile }}</i></strong>
    <br>
    @if ($phoneNum)
        {{ trans('general.phone_num') }} <strong><i>{{ $phoneNum }}</i></strong>
    @endif
</p>

<p>
    {{ trans('online.this_is_automatic_email') }}:<br>
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
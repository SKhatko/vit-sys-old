<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>

<body>
<p>
    {{ trans('online.client_greeting', ['honorific' => trans('crm.honorific_'.$honorific), 'first_name' => $firstName, 'last_name' => $lastName]) }}
    ,
</p>

<p>
    {!! trans('online.you_recently_made_a_reservation_at_restaurant_name', ['name' => '<strong>'.$restaurantName.'</strong>']) !!}
    .
</p>

<p>
    <strong><span style="color:#a62222">{{ trans('reception.reservation_info') }}</span></strong>:<br>
    {{ trans('online.reference_id') }}: <strong><i>{{ $referenceId }}</i></strong><br>
    {{ trans('online.date_and_time') }}: <strong><i>{{ $time }}</i></strong><br>
    {{ trans('general.persons_num') }}: <strong><i>{{ $personsNum }}</i></strong><br>

    @if ($notes)
        {{ trans('online.additional_notes') }}: {{ $notes }}
    @endif
</p>

<p>
    <strong><span style="color:#a62222">{{ trans('online.restaurant_details') }}</span></strong>:<br>
    <strong><i>{{ $restaurantName }}</i></strong>
    <br>
    <strong><i>{{ \App\Config::$address }}, {{ \App\Config::$city }}</i></strong>
    <br>
    <strong><i>{{ \App\Config::$email }}</i></strong>
    <br>
    <strong><i>
            {{ \App\Config::$phone }}
            @if (\App\Config::$mobile)
                / {{ \App\Config::$mobile }}
            @endif

            @if (\App\Config::$phone_2)
                / {{ \App\Config::$phone_2 }}
            @endif
        </i></strong>
</p>

<p>
    {{ trans('online.for_any_inquiries_contact_restaurant') }}.
</p>

<p>
    {{ trans('online.cancel_reservation_url') }}<br>
    <a href="{{ action('Online\ReservationsController@cancelReservation', [$referenceId, $cancelToken]) }}"
       target="_blank">{{ action('Online\ReservationsController@cancelReservation', [$referenceId, $cancelToken]) }}</a>
</p>

<p>
    {{ trans('online.client_email_good_wishing') }}.
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
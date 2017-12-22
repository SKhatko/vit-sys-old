@extends('crm.index')

@section('content')
    <div class="client">

        @include('components.back')

        <div class="client-info">

            <div class="client-info__header">
                <div title=" {{ trans('general.name') }}" class="client-info__header--name">
                    {{ trans('general.name') }}
                </div>

                <div title="{{ trans('general.phone_num') }}" class="client-info__header--phone">
                    {{ trans('general.phone_num') }}
                </div>

                <div title="{{ trans('general.mobile') }}" class="client-info__header--mobile">
                    {{ trans('general.mobile') }}
                </div>

                <div title="{{ trans('crm.email') }}" class="client-info__header--email">
                    {{ trans('crm.email') }}
                </div>

                <div title="{{ trans('crm.client_status') }}" class="client-info__header--status">
                    {{ trans('crm.client_status') }}
                </div>

                <div title="{{ trans('crm.sticky_note') }}" class="client-info__header--note">
                    {{ trans('crm.sticky_note') }}
                </div>

                <div title="{{ trans('general.edit') }}" class="client-info__header--edit">
                    {{ trans('general.edit') }}
                </div>

            </div>

            <div class="client-info__client">
                <a href="{{ action('ClientsController@show', [$client->id]) }}"
                   class="client-info__client--name" title="{{ $client->name }}">
                    {{ $client->name }}
                </a>

                <div class="client-info__client--phone" title="{{ $client->phone }}">
                    {{ $client->phone }}
                </div>

                <div class="client-info__client--mobile" title="{{ $client->mobile }}">
                    {{ $client->mobile }}
                </div>

                <div class="client-info__client--email" title="{{ $client->email }}">
                    {{ $client->email }}
                </div>

                <div class="client-info__client--status" title="{{ $statuses[$client->status_id] }}">
                    {{ $statuses[$client->status_id] }}
                </div>

                <div class="client-info__client--note" title="{{ $client->sticky_note }}">
                    {{ $client->sticky_note }}
                </div>

                <div class="client-info__client--edit">
                    <svg class="client-info__client--edit-icon js-client-info__client--edit-icon">
                        <use xlink:href="#icon-edit"></use>
                    </svg>
                </div>

            </div>

        </div>

        @if(count($reservations))

            <div class="client-top">
                <div class="client-top--name">
                    {{ $client->name }}
                </div>

                <div class="client-top--count">
                    {{ count($reservations) }} Reservations
                </div>
            </div>

            <div class="client-reservations">

                <div class="client-reservations__header">

                    <div title="{{ trans('general.date') }}" class="client-reservations__header--date">
                        {{ trans('general.date') }}
                    </div>

                    <div title="{{ trans('general.time') }}" class="client-reservations__header--time">
                        {{ trans('general.time') }}
                    </div>

                    <div title="{{ trans('general.status') }}" class="client-reservations__header--status">
                        {{ trans('general.status') }}
                    </div>

                </div>

                @foreach ($reservations as $reservation)
                    <div class="client-reservations__reservation">

                        <div title="{{ $reservation->date }}" class="client-reservations__reservation--date">
                            {{ $reservation->date }}
                        </div>

                        <div title="{{ $reservation->time }}" class="client-reservations__reservation--time">
                            {{ $reservation->time }}
                        </div>

                        <div title="{{ $reservation->status->name }}" class="client-reservations__reservation--status">
                            {{ $reservation->status->name }}
                        </div>

                    </div>
                @endforeach


            </div>
        @endif
    </div>


@stop

@section('helpers')

    <!----- edit ITEM MODAL ------>
    @component('components.modal', ['class' => 'js-client__modal-edit client__modal-edit'])

        {{ Form::open(['method' => 'PATCH', 'action' => ['ClientsController@update', $client->id]]) }}

        <div class="restaurant-sections__modal-edit--title">
            Edit Client Profile
        </div>

        {!! Form::label('gender', trans('general.gender'), ['class' => 'label']) !!}
        {!! Form::select('gender', $genders, $client->gender, ['class' => 'select', 'required']) !!}

        {!! Form::label('first_name', trans('general.first_name'), ['class' => 'label']) !!}
        {!! Form::text('first_name', $client->first_name, ['class' => 'input', 'required']) !!}

        {!! Form::label('last_name', trans('general.last_name'), ['class' => 'label']) !!}
        {!! Form::text('last_name', $client->last_name, ['class' => 'input', 'required']) !!}

        {!! Form::label('phone', trans('general.phone_num'), ['class' => 'label']) !!}
        {!! Form::text('phone', $client->phone, ['class' => 'input']) !!}

        {!! Form::label('mobile', trans('general.mobile'), ['class' => 'label']) !!}
        {!! Form::text('mobile', $client->mobile, ['class' => 'input']) !!}

        {!! Form::label('email', trans('crm.email'), ['class' => 'label']) !!}
        {!! Form::email('email', $client->email, ['class' => 'input']) !!}

        {!! Form::label('status_id', trans('crm.client_status'), ['class' => 'label']) !!}
        {!! Form::select('status_id', $statuses, $client->status_id, ['class' => 'select']) !!}

        {!! Form::label('sticky_note', trans('crm.sticky_note'), ['class' => 'label']) !!}
        {!! Form::textarea('sticky_note', $client->sticky_note, ['class' => 'input']) !!}

        {{ Form::button(trans('general.update'), ['type'=>'submit','class' => 'restaurant-sections__modal-edit--submit']) }}

        {{ Form::close() }}

    @endcomponent

@stop

@section('script')
    <script>
        new Crm().client()
    </script>
@stop
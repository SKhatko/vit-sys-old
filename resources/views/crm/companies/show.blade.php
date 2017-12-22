@extends('crm.index')

@section('content')

    <div class="company">

        @include('components.back')

        @if(count($reservations))

            <div class="company-top">
                <div class="company-top--name">
                    {{ $company->name }}
                </div>

                <div class="company-top--count">
                    {{ $company->reservations_count }} Reservations
                </div>
            </div>

            <div class="company-reservations">

                <div class="company-reservations__header">

                    <div title="{{ trans('crm.client') }}" class="company-reservations__header--client">
                        {{ trans('crm.client') }}
                    </div>

                    <div title="{{ trans('general.date') }}" class="company-reservations__header--date">
                        {{ trans('general.date') }}
                    </div>

                    <div title="{{ trans('general.time') }}" class="company-reservations__header--time">
                        {{ trans('general.time') }}
                    </div>

                    <div title="{{ trans('general.phone_num') }}" class="company-reservations__header--phone">
                        {{ trans('general.phone_num') }}
                    </div>

                    <div title="{{ trans('general.mobile') }}" class="company-reservations__header--mobile">
                        {{ trans('general.mobile') }}
                    </div>

                    <div title="{{ trans('general.email') }}" class="company-reservations__header--email">
                        {{ trans('general.email') }}
                    </div>

                    <div title="{{ trans('general.status') }}" class="company-reservations__header--status">
                        {{ trans('general.status') }}
                    </div>

                </div>

                @foreach ($reservations as $reservation)
                    <div class="company-reservations__reservation">


                        <div title="{{ $reservation->client->name }}" class="company-reservations__reservation--client">
                            {{ $reservation->client->name }}
                        </div>

                        <div title="{{ $reservation->date }}" class="company-reservations__reservation--date">
                            {{ $reservation->date }}
                        </div>

                        <div title="{{ $reservation->time }}" class="company-reservations__reservation--time">
                            {{ $reservation->time }}
                        </div>

                        <div title="{{ $reservation->client->phone }}" class="company-reservations__reservation--phone">
                            {{ $reservation->client->phone }}
                        </div>

                        <div title="{{ $reservation->client->mobile }}" class="company-reservations__reservation--mobile">
                            {{ $reservation->client->mobile }}
                        </div>

                        <div title="{{ $reservation->client->email }}" class="company-reservations__reservation--email">
                            {{ $reservation->client->email }}
                        </div>

                        <div title="{{ $reservation->status->name }}" class="company-reservations__reservation--status">
                            {{ $reservation->status->name }}
                        </div>

                    </div>
                @endforeach

            </div>
        @endif
    </div>


@stop

@section('script')
    <script>
        new Crm().company()
    </script>
@stop
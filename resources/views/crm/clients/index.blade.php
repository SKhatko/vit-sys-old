@extends('crm.index')

@section('content')

    <div class="clients">

        {{ Form::open(['method' => 'post', 'action' => 'ClientsController@filter']) }}

        <div class="clients-header">

            <div class="clients-header__name">
                {{ Form::label('name', trans('crm.client_name'), ['class' => 'label']) }}
                {{ Form::text('name', $filterName, ['class' => 'input']) }}
            </div>

            <div class="clients-header__phone">
                {{ Form::label('phone', trans('general.phone_or_mobile'), ['class' => 'label']) }}
                {{ Form::text('phone', $filterPhone, ['class' => 'input']) }}
            </div>

            <div class="clients-header__email">
                {{ Form::label('email', trans('crm.email'), ['class' => 'label']) }}
                {{ Form::text('email', $filterEmail, ['class' => 'input']) }}
            </div>

            <div class="clients-header__status">
                {{ Form::label('status_id', trans('crm.client_status'), ['class' => 'label']) }}
                {{ Form::select('status_id', $statuses, $filterStatusId, ['class' => 'select', 'placeholder' => 'Filter By Status']) }}
            </div>

            <div class="clients-header__control">
                {{ Form::button(trans('general.filter'), ['type'=>'submit', 'class' => 'clients-header__control--submit']) }}

                <a href="{{ action('ClientsController@clearFilters') }}"
                   class="clients-header__control--clear">
                    <svg class="clients-header__control--clear-icon">
                        <use xlink:href="#icon-cross"></use>
                    </svg>
                </a>
            </div>

        </div>

        {{ Form::close() }}

        <div class="clients-content">

            <div class="clients-content__header">
                <div title="{{ trans('general.name') }}" class="clients-content__header--name">
                    {{ trans('general.name') }}
                </div>

                <div title="{{ trans('general.phone_num') }}" class="clients-content__header--phone">
                    {{ trans('general.phone_num') }}
                </div>

                <div title="{{ trans('general.mobile') }}" class="clients-content__header--mobile">
                    {{ trans('general.mobile') }}
                </div>

                <div title="{{ trans('crm.email') }}" class="clients-content__header--email">
                    {{ trans('crm.email') }}
                </div>

                <div title="{{ trans('reception.reservations') }}" class="clients-content__header--reservations">
                    {{ trans('reception.reservations') }}
                </div>

                <div title="{{ trans('crm.client_status') }}" class="clients-content__header--status">
                    {{ trans('crm.client_status') }}
                </div>

            </div>

            <div class="clients-content__create">
                <a href="javascript:" class="clients-content__create-link js-clients-content__create-link">
                    <svg class="clients-content__create-link--icon">
                        <use xlink:href="#icon-thin-cross"></use>
                    </svg>

                    <span class="clients-content__create-link--label">
                        Add New Client
                    </span>
                </a>
            </div>


            @foreach ($clients as $client)
                <div class="clients-content__client">
                    <a href="{{ action('ClientsController@show', [$client->id]) }}"
                       class="clients-content__client--name"
                       title="{{ $client->name }}">
                        {{ $client->name }}
                    </a>

                    <div class="clients-content__client--phone" title="{{ $client->phone }}">
                        {{ $client->phone }}
                    </div>

                    <div class="clients-content__client--mobile" title="{{ $client->mobile }}">
                        {{ $client->mobile }}
                    </div>

                    <div class="clients-content__client--email" title="{{ $client->email }}">
                        {{ $client->email }}
                    </div>

                    <div class="clients-content__client--reservations">
                        {{ $client->active_reservations_count }}
                    </div>

                    <div class="clients-content__client--status" title="{{ trans('crm.'.$client->status->name) }}">
                        {{ trans('crm.'.$client->status->name) }}
                    </div>
                </div>
            @endforeach

        </div>

        {{ $clients->links() }}


    </div>

@stop

@section('helpers')

    <!----- edit ITEM MODAL ------>
    @component('components.modal', ['class' => 'js-clients__modal-create clients__modal-create'])

        {{ Form::open(['method' => 'POST', 'action' => 'ClientsController@store']) }}

        <div class="restaurant-sections__modal-edit--title">
            Create Client Profile
        </div>

        {!! Form::label('gender', trans('general.gender'), ['class' => 'label']) !!}
        {!! Form::select('gender', $genders, NULL, ['class' => 'select', 'required']) !!}

        {!! Form::label('first_name', trans('general.first_name'), ['class' => 'label']) !!}
        {!! Form::text('first_name', NULL, ['class' => 'input', 'required']) !!}

        {!! Form::label('last_name', trans('general.last_name'), ['class' => 'label']) !!}
        {!! Form::text('last_name', NULL, ['class' => 'input', 'required']) !!}

        {!! Form::label('phone', trans('general.phone_num'), ['class' => 'label']) !!}
        {!! Form::text('phone', NULL, ['class' => 'input']) !!}

        {!! Form::label('mobile', trans('general.mobile'), ['class' => 'label']) !!}
        {!! Form::text('mobile', NULL, ['class' => 'input']) !!}

        {!! Form::label('email', trans('crm.email'), ['class' => 'label']) !!}
        {!! Form::email('email', NULL, ['class' => 'input']) !!}

        {!! Form::label('status_id', trans('crm.client_status'), ['class' => 'label']) !!}
        {!! Form::select('status_id', $statuses, NULL, ['class' => 'select']) !!}

        {!! Form::label('sticky_note', trans('crm.sticky_note'), ['class' => 'label']) !!}
        {!! Form::textarea('sticky_note', NULL, ['class' => 'input']) !!}

        {{ Form::button(trans('general.create'), [ 'type'=>'submit', 'class' => 'restaurant-sections__modal-edit--submit']) }}

        {{ Form::close() }}

    @endcomponent

@stop

@section('script')
    <script>
        new Crm().clients()
    </script>
@stop
@extends('crm.index')

@section('content')

    <div class="companies">

        {{ Form::open(['method' => 'post', 'action' => 'CompaniesController@filter']) }}

        <div class="companies-header">

            <div class="companies-header__name">
                {{ Form::label('name', trans('crm.company_name'), ['class' => 'label']) }}
                {{ Form::text('name', $filterName, ['class' => 'input']) }}
            </div>

            <div class="companies-header__control">
                {{ Form::button(trans('general.filter'), ['type'=>'submit','class' => 'companies-header__control--submit']) }}

                <a href="{{ action('CompaniesController@clearFilters') }}"
                   class="companies-header__control--clear">
                    <svg class="companies-header__control--clear-icon">
                        <use xlink:href="#icon-cross"></use>
                    </svg>
                </a>
            </div>

        </div>

        {{ Form::close() }}

        @if(count($companies))
            <div class="companies-content">

                <div class="companies-content__header">
                    <div title="{{ trans('general.name') }}" class="companies-content__header--name">
                        {{ trans('general.name') }}
                    </div>

                    <div title="{{ trans('reception.reservations') }}" class="companies-content__header--reservations">
                        {{ trans('reception.reservations') }}
                    </div>

                    <div title="{{ trans('general.edit') }}" class="companies-content__header--edit">
                        {{ trans('general.edit') }}
                    </div>

                </div>

                @foreach ($companies as $company)
                    <div class="companies-content__company js-companies-content__company"
                         data-company-name="{{ $company->name }}" data-company-id="{{ $company->id }}">
                        <div class="companies-content__company--name" title="{{ $company->name }}">
                            <a href="{{ action('CompaniesController@show', [$company->id]) }}"
                               class="companies-content__company--name-link">
                                {{ $company->name }}
                            </a>
                        </div>

                        <div class="companies-content__company--reservations">
                            {{ $company->reservations_count }}
                        </div>

                        <div class="companies-content__company--edit" title="Edit">
                            <svg class="companies-content__company--edit-icon js-companies-content__company--edit-icon">
                                <use xlink:href="#icon-edit"></use>
                            </svg>
                        </div>

                    </div>
                @endforeach
            </div>

        @else
            <div class="companies-error__text">
                Nothing was found on you request
            </div>
        @endif

        {{ $companies->links() }}

    </div>


@stop

@section('helpers')

    <!----- edit ITEM MODAL ------>
    @component('components.modal', ['class' => 'js-companies__modal-edit companies__modal-edit'])

        {{ Form::open(['method' => 'PATCH', 'action' => ['CompaniesController@index']]) }}

        <div class="restaurant-sections__modal-edit--title">
            Edit Company Profile
        </div>

        {!! Form::label('name', trans('general.company_name'), ['class' => 'label']) !!}
        {!! Form::text('name', null, ['class' => 'input js-companies__modal-edit--name', 'required', 'autofocus']) !!}

        {{ Form::submit(trans('general.update'), ['class' => 'companies__modal-edit--submit']) }}

        {{ Form::close() }}

    @endcomponent

@stop

@section('script')
    <script>
        new Crm().companies();
    </script>
@stop
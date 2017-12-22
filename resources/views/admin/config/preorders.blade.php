@extends('admin.index')

@section('content')

    <div class="admin-preorders">

        {!! Form::open(['method' => 'post', 'action' => 'ConfigController@postPreorders']) !!}

        <div class="admin-preorders__header">

            <div class="admin-preorders__header-prices">

                <div class="admin-preorders__header--checkbox">
                    {{ Form::checkbox('display_images', true, $settings['display_images'], ['id' => 'display_images']) }}
                    <label for="display_images"></label>

                </div>
                {{ Form::label('display_images', trans('menu.display_images'), ['class' => 'admin-preorders__header--label']) }}

            </div>

            <div class="admin-preorders__header-images">
                <div class="admin-preorders__header--checkbox">
                    {{ Form::checkbox('display_prices', true, $settings['display_prices'], ['id' => 'display_prices']) }}
                    <label for="display_prices"></label>
                </div>
                {{ Form::label('display_prices', trans('menu.display_prices'), ['class' => 'admin-preorders__header--label']) }}
            </div>

            <div class="admin-preorders__header-hours">
                {{ Form::number('hours_limit', $settings['hours_limit'], ['class' => 'admin-preorders__header-hours--input']) }}
                {{ Form::label('hours_limit', 'Block preorders hours before reservations', ['class' => 'admin-preorders__header-hours--label']) }}
            </div>

            {!! Form::button(trans('general.submit'), ['type'=>'submit', 'class' => 'admin-preorders__header--submit']) !!}

        </div>

        {!! Form::close() !!}

    </div>

@stop

@section('script')
    <script>
        new Admin()
    </script>
@stop
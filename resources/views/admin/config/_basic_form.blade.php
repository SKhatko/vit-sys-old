<div class="admin-basic">

    {{ Form::open(['method' => 'post', 'action' => 'ConfigController@postBasic']) }}

    <div class="admin-basic__header">

        <div class="admin-basic__header-orange">
            {!! Form::input('number', 'orange_num', $config::$orange_num, ['class' => 'admin-basic__header--input']) !!}
            {!! Form::label('orange_num', trans('admin.orange_light_count_label'), ['class' => 'admin-basic__header--label']) !!}
        </div>

        <div class="admin-basic__header-red">
            {!! Form::input('number', 'red_num', $config::$red_num, ['class' => 'admin-basic__header--input']) !!}
            {!! Form::label('red_num', trans('admin.red_light_count_label'), ['class' => 'admin-basic__header--label']) !!}
        </div>

        {!! Form::button('Save', ['type'=>'submit', 'class' => 'admin-basic__header-submit']) !!}

    </div>

    <div class="admin-basic__content">

        <div class="admin-basic__info">
            <div class="admin-basic__info--header">
                    <span>
                        Information
                    </span>
                <span>
                        1
                    </span>
            </div>

            <div class="admin-basic__info--content">

                {{ Form::label('restaurant_name', trans('general.restaurant_name'), ['class' => 'label']) }}
                {{ Form::text('restaurant_name', $config::$restaurant_name, ['class' => 'input', 'required']) }}

                {!! Form::label('phone', trans('general.phone_or_mobile'), ['class' => 'label']) !!}
                {!! Form::text('phone', $config::$phone, ['class' => 'input', 'required']) !!}

                {!! Form::label('mobile', trans('general.mobile'), ['class' => 'label']) !!}
                {!! Form::text('mobile', $config::$mobile, ['class' => 'input']) !!}

                {!! Form::label('email', trans('auth.email'), ['class' => 'label']) !!}
                {!! Form::email('email', $config::$email, ['class' => 'input', 'required']) !!}
            </div>

        </div>

        <div class="admin-basic__info">
            <div class="admin-basic__info--header">
                    <span>
                    </span>
                <span>
                        2
                    </span>
            </div>

            <div class="admin-basic__info--content">

                {{ Form::label('website', trans('general.website'), ['class' => 'label']) }}
                {{ Form::url('website', $config::$website, ['class' => 'input']) }}

                {!! Form::label('address', trans('general.address'), ['class' => 'label']) !!}
                {!! Form::text('address', $config::$address, ['class' => 'input', 'required']) !!}


                {!! Form::label('zip_code', trans('general.zip_code'), ['class' => 'label']) !!}
                {!! Form::text('zip_code', $config::$zip_code, ['class' => 'input', 'required']) !!}

                {!! Form::label('language', trans('general.language'), ['class' => 'label']) !!}
                {!! Form::select('language', $config::$availableLanguages, $config::$language, ['class' => 'input', 'required']) !!}

            </div>

        </div>
        <div class="admin-basic__info">

            <div class="admin-basic__info--header">
                    <span>
                    </span>
                <span>
                        3
                    </span>
            </div>

            <div class="admin-basic__info--content">
                {!! Form::label('city', trans('general.city'), ['class' => 'label']) !!}
                {!! Form::text('city', $config::$city, ['class' => 'input', 'required']) !!}

                {!! Form::label('country', trans('general.country'), ['class' => 'label']) !!}
                {!! Form::select('country', $config::$availableCountries, $config::$country, ['class' => 'input', 'required']) !!}

                {!! Form::label('timezone', trans('general.timezone'), ['class' => 'label']) !!}
                {!! Form::select('timezone', $config::$availableTimezones, $config::$timezone, ['class' => 'input', 'required']) !!}

                {!! Form::label('currency', trans('general.currency'), ['class' => 'label']) !!}
                {!! Form::select('currency', $currencies, $config::$currency, ['class' => 'input', 'required']) !!}
            </div>

        </div>
    </div>

    <div class="admin-basic__footer">

        {!! Form::submit(trans('general.submit'), ['class' => 'admin-basic__footer--submit']) !!}

    </div>

    {!! Form::close() !!}


<!-- TODO check how decimal_point works -->
    {{--                {!! Form::label('decimal_point', trans('general.decimal_point'), ['class' => 'label']) !!}--}}
    {{--                {!! Form::select('decimal_point', ['.' => '. ('.trans('general.dot').')', ',' => ', ('.trans('general.comma').')'], $config::$decimal_point, ['class' => 'input', 'required']) !!}--}}


</div>

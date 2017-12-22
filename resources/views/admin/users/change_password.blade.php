@extends('admin.index')

@section('content')

    <div class="admin-password">

        {!! Form::open(['method' => 'post', 'action' => 'UsersController@updatePassword']) !!}

        <div class="admin-password__header">
            Change Admin Password
        </div>

        <div class="admin-password__content">
            {!! Form::label('current_password', trans('admin.current_password'), ['class' => 'label']) !!}
            {!! Form::password('current_password', ['class' => 'input', 'required']) !!}

            {!! Form::label('new_password', trans('admin.new_password'), ['class' => 'label']) !!}
            {!! Form::password('new_password', ['class' => 'input', 'required']) !!}

            {!! Form::label('new_password_confirmation', trans('admin.new_password_confirmation'), ['class' => 'label']) !!}
            {!! Form::password('new_password_confirmation', ['class' => 'input', 'required']) !!}

            <div class="admin-password__content-footer">
                {!! Form::button(trans('general.submit'), ['type'=>'submit', 'class' => 'admin-password__content-footer--submit']) !!}
            </div>

        </div>

        {!! Form::close() !!}
    </div>
@stop

@section('script')
    <script>
        new Admin()
    </script>
@stop

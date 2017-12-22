@extends('online.reservation.step_master')

@section('content')
    <div class="container">
        <div class="row">

            {!! Form::open(['method' => 'post', 'action' => 'Online\ReservationsController@postStep2']) !!}

            <div class="col-md-6 col-sm-6 col-xs-12 mtop">

                <div class="form-group">
                    <label for="honorific">&nbsp;</label>
                    <select name="honorific" class="form-control" required style="margin-top:2px;">
                        <option value="mr">{{ trans('crm.honorific_mr') }}</option>
                        <option value="mrs">{{ trans('crm.honorific_mrs') }}</option>
                        <?php
                        /*
                        <option value="ms_dr">{{ trans('crm.honorific_ms_or_dr') }}</option>
                        */
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    {!! Form::label('first_name', trans('general.first_name').' *') !!}
                    {!! Form::text('first_name', $firstName, ['class' => 'form-control', 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('last_name', trans('general.last_name').' *') !!}
                    {!! Form::text('last_name', $lastName, ['class' => 'form-control', 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('email', trans('auth.email').' *') !!}
                    {!! Form::email('email', $email, ['class' => 'form-control', 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('mobile', trans('general.mobile').' *') !!}
                    {!! Form::text('mobile', $mobile, ['class' => 'form-control', 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('phone_num', trans('general.phone_num')) !!}
                    {!! Form::text('phone_num', $phoneNum, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12 mtop mnone-xs">
                <div class="form-group">
                    {!! Form::label('notes', trans('online.notes')) !!}
                    {!! Form::textarea('notes', $notes, ['class' => 'form-control']) !!}
                </div>
            </div>

        </div>
        <div class="row">

            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::submit(trans('general.continue'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@stop
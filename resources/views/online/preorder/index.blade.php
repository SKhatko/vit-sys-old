@extends('online.preorder.master')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <p class="alert alert-info mtop">
                    <span>{{ trans('online.reference_id') }}: <strong>{{ $identifier }}</strong></span><br>
                    <span>{{ trans('online.reservation_date_and_time') }}
                        : <strong>{{ $reservation->date }} {{ date("H:i", strtotime($reservation->time)) }}</strong></span><br>
                </p>

            </div>
        </div>

        <div class="row">

            <div class="col-md-6 col-sm-6 col-xs-12 mboth">
                <div class="content-box">
                    <p>{{ trans('online.create_new_preorder') }}</p>

                    {!! Form::open(['method' => 'post', 'action' => 'Online\PreordersController@create']) !!}
                    <div class="form-group">
                        {!! Form::label('name', trans('online.full_name').':') !!}
                        {!! Form::text('name', NULL, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::submit(trans('general.create'), ['class' => 'btn btn-success']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12 mboth">
                <div class="content-box">
                    <p>
                        {{ trans('online.edit_existing_preorder') }}
                    </p>


                    <div class="form-group">
                        {!! Form::label('id', trans('online.i_am').':') !!}
                        {!! Form::select('id', $preorders, NULL, ['class' => 'form-control', 'id' => 'preorder-sel']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::open(['method' => 'post', 'action' => 'Online\PreordersController@setId', 'style' => 'display:inline-block']) !!}
                        <input type="hidden" name="id" class="hidden-id-holder"/>
                        {!! Form::submit(trans('general.edit'), ['class' => 'btn btn-warning disabled', 'id' => 'edit-btn', 'disabled']) !!}
                        {!! Form::close() !!}

                        {!! Form::open(['method' => 'post', 'action' => 'Online\PreordersController@delete', 'style' => 'display:inline-block;', 'class' => 'pull-right']) !!}
                        <input type="hidden" name="id" class="hidden-id-holder"/>
                        {!! Form::submit(trans('online.cancel_order'), ['class' => 'btn btn-danger disabled', 'id' => 'delete-btn', 'disabled']) !!}
                        {!! Form::close() !!}

                    </div>


                </div>
            </div>


        </div>

        @if (!$outdated)
            <div class="row">
                <div class="col-md-12">
                    <p class="alert alert-warning">
                        {!! trans('online.hours_limit_msg_with_hours_limit', ['hours_limit' => '<strong>'.$hoursLimit.'</strong>']) !!}
                    </p>

                </div>
            </div>
        @endif

    </div>
@stop

@section('scripts')
    <script>
        function updateForms() {

            var preorderId = $("#preorder-sel").val();
            var $deleteButton = $("#delete-btn");
            var $editButton = $("#edit-btn");


            if (preorderId != null && preorderId != "") {

                $deleteButton.removeClass('disabled');
                $deleteButton.prop('disabled', false);

                $editButton.removeClass('disabled');
                $editButton.prop('disabled', false);

                $(".hidden-id-holder").val(preorderId);
            }
            else {
                $deleteButton.addClass('disabled');
                $deleteButton.prop('disabled', true);

                $editButton.addClass('disabled');
                $editButton.prop('disabled', true);

                $(".hidden-id-holder").val("");
            }
        }

        $(function () {
            updateForms(); //in case value was selected on load. In case of clicking back for example
            $("#preorder-sel").change(function () {
                updateForms();
            });
        });
    </script>
@stop
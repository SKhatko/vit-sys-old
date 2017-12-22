@extends('reception.index')

@section('content')
    <div class="reception-new">

        {!! Form::model($reservation = new \App\Reservation, ['action' => 'ReservationsController@store', 'class' => 'reservation-form', 'files' => true]) !!}
        @include('reception.reservations._form', ['submitButtonText' => trans('reception.store_reservation')])
        {!! Form::close() !!}

    </div>
@stop

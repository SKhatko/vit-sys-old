@extends('reception.index')

@section('content')

    <div class="reception-back">

        @include('components.back')

    </div>

    <div class="reception">
        {!! Form::model($reservation, ['method' => 'PATCH', 'action' => ['ReservationsController@update', $reservation->id], 'files' => true]) !!}
        @include('reception.reservations._form', ['submitButtonText' => trans('reception.update_reservation')])
        {!! Form::close() !!}
    </div>

    <div class="logs">
        <h3>Changes Log</h3>
        <?php
        $changes = $reservation->reservation_changes;
        ?>

        @if (!count($changes))
            No Records Found
        @else
            @foreach ($changes as $change)
                <p>
                    <strong>{{ $change->created_at }}</strong> :
                    {{ $change->action }}
                    @if ($change->user)
                        <strong>By {{ $change->user }}</strong>
                    @endif
                </p>
            @endforeach
        @endif
    </div>
@stop

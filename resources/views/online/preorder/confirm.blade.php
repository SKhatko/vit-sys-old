@extends('online.preorder.master')

@section('content')
    <div class="container">

        {!! Form::open(['method' => 'post', 'action' => 'Online\PreordersController@postConfirm']) !!}


        <div class="row">
            <div class="col-md-12 mtop">

                <p class="alert alert-info">
                    <span>{{ trans('online.reference_id') }}: <strong>{{ $identifier }}</strong></span><br>
                    <span>{{ trans('online.reservation_date_and_time') }}
                        : <strong>{{ $reservation->date }} {{ date("H:i", strtotime($reservation->time)) }}</strong></span><br>
                </p>
            </div>
        </div>

        <div class="row">

            <div class="col-md-12 mtop">

                <div class="form-group">
                    {!! Form::label('name', trans('general.name').' *') !!}
                    {!! Form::text('name', $preorder->name, ['class' => 'form-control', 'required']) !!}
                </div>
            </div>
        </div>

        <!-- Menu -->
        <div class="row">
            <div class="col-md-12">

                <label>{{ trans('online.preorder_summary') }}</label>

                <div class="content-box">
                    <div class="order-summary-holder">

                        @if (isset($cartData['items']))
                            @foreach ($cartData['items'] as $itemRecord)
                                <span><strong>{{ $itemRecord['quantity'] }}
                                        x {{ $itemRecord['item']->translatedName($menuLanguage) }}</strong></span><br>
                                {{ $itemRecord['item']->translatedDescription($menuLanguage) }}
                                <hr>
                            @endforeach
                        @endif


                        @if (isset($cartData['groups']))
                            @foreach ($cartData['groups'] as $groupRecord)
                                <span><strong>{{ $groupRecord['quantity'] }}
                                        x {{ $groupRecord['group']->translatedName($menuLanguage) }}</strong></span><br>
                                <ul>
                                    @foreach ($groupRecord['items'] as $item)
                                        <li>{{ $item->translatedName($menuLanguage) }}</li>
                                    @endforeach
                                </ul>
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <!-- Wishes -->
        <div class="row">
            <div class="col-md-12 mtop">
                <div class="form-group">
                    {!! Form::label('notes', trans('online.notes')) !!}
                    <textarea class="form-control" name="notes">{{ $preorder->notes }}</textarea>
                </div>

                <div class="form-group">
                    {!! Form::submit($submitBtnText, ['class' => 'btn btn-primary', 'id' => 'submit-btn']) !!}
                </div>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@stop
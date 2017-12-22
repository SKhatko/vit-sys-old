@extends('kitchen.master')

@section('content')

    <div class="col-md-12">
        <div class="hidden-print">
            <ul class="nav nav-pills hidden-print" style="display:inline-block;">
                <li class="active"><a data-toggle="pill" href="#detailed-view">{{ trans('kitchen.detailed_view') }}</a>
                </li>
                <li><a data-toggle="pill" href="#summary-view">{{ trans('kitchen.summary_view') }}</a></li>
            </ul>

            <a href="javascript:;" class="kitchen_summary_print pull-right " onclick="window.print();"
               title="{{ trans('kitchen.print_preorder_summary') }}">
                <img src="{{ asset('img/printer.png') }}" alt="{{ trans('reception.print_reservations') }}">
            </a>

            <a href="{{ action('PreordersController@menu', [$reservationId]) }}" class="btn btn-primary pull-right"
               target="_blank">{{ trans('kitchen.view_preorder_menu') }}</a>

            <hr>
        </div>

        <div class="tab-content">

            <div id="detailed-view" class="tab-pane fade in active">
                @foreach ($preorders as $preorder)
                    <div class="preorder-holder mbottom">
                        <h4>{{ $preorder->name }}</h4>

                        @foreach ($preorder->items as $item)
                            <hr>
                            {{ $item->pivot->quantity }} x {{ $item->translatedName($language) }}<br>
                        @endforeach

                        @foreach ($preorder->groups as $group)
                            <hr>
                            {{ $group->pivot->quantity }} x {{ $group->translatedName($language) }}<br>

                            @if ($group->pivot->items)
                                <?php $groupItems = \App\MenuItem::with('translations')->findMany(array_map('intval', explode(',', $group->pivot->items))); ?>

                                @foreach ($groupItems as $item)
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    - {{ $item->translatedName($language) }}<br>
                                @endforeach
                            @endif

                        @endforeach



                        @if ($preorder->notes)
                            <hr>

                            <strong>{{ trans('online.notes') }}:</strong> <span
                                    color="orange">{{ $preorder->notes }}</span>
                        @endif
                    </div>
                @endforeach
            </div>

            <div id="summary-view" class="tab-pane fade">
                <div class="preorder-holder">
                    {!! $preordersSummary !!}
                </div>
            </div>
        </div>
    </div>
@stop
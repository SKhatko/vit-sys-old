@extends('analytics.index')

@section('content')

    <div class="col-md-12 mbottom">
        <div class="filters-section">

            {!! Form::open(['method' => 'POST', 'action' => 'AnalyticsController@setReservationsSourceReportPeriod', 'id' => 'date-range-form']) !!}

            <div class="col-md-4 col-sm-8">
                <div class="form-group">
                    <input type="text" name="date_range" id="date-range-picker"
                           class="form-control light-border inlined xs-mbottom"/>
                </div>
            </div>

            {!! Form::close() !!}
            <div class="clear"></div>
        </div>
    </div>

    <div class="col-md-12 mboth pnone">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="chart-container" data-report="reservations"></div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="chart-container" data-report="persons"></div>
        </div>

    </div>

    <div class="col-md-12 mboth">
        <table class="table table-border table-striped show-after-load" id="data-table" style="display:none;">

            <tr>
                <th>{{ trans('analytics.ref') }}</th>
                <th>{{ trans('analytics.reservations') }}</th>
                <th>{{ trans('analytics.persons') }}</th>
            </tr>

        </table>
    </div>
@stop


@section('script')
    <script>
        new Analytic({
            dateFrom: '{{ $dateFrom }}',
            dateTo: '{{ $dateTo }}',
            decimalPoint: '{{ \App\Config::$decimal_point }}',
            transOther: '{{ trans('analytics.other') }}',
            transActiveReservations: '{{ trans('analytics.active_online_reservations') }}',
            transActivePersons: '{{ trans('analytics.active_online_persons') }}',
        }).reservationsStats();
    </script>
@stop
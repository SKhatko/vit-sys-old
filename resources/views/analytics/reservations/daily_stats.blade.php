@extends('analytics.index')

@section('content')

    <div class="col-md-12 mbottom">
        <div class="filters-section">

            {!! Form::open(['method' => 'POST', 'action' => 'AnalyticsController@setReservationsDailyReportDateRange', 'id' => 'date-range-form']) !!}

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

    <div class="col-md-12 pnone mbottom">
        <div class="stats-section">
            <div class="col-md-15 col-sm-4 col-xs-6 mbottom">
                <h3>{{ trans('analytics.normal_reservations') }}</h3>
                <div class="loader stat-loader calign">
                    <img src="{{ asset('/img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}"/>
                </div>
                <span class="stat-icon fa fa-list" title="{{ trans('analytics.reservations') }}"></span>
                <span id="total-reservations-stat-holder" class="stat-holder reservations-stat"
                      style="color:#000;"></span>
                <br>
                <span class="stat-icon fa fa-user" title="{{ trans('analytics.persons') }}"></span>
                <span id="total-persons-stat-holder" class="stat-holder persons-stat" style="color:#000;"></span>
            </div>


            <?php
            /*
            <div class="col-md-2 col-sm-4 col-xs-6 mbottom">
                <h3>{{ trans('analytics.persons') }}</h3>
                <div class="loader stat-loader calign">
                    <img src="{{ asset('/img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}" />
                </div>
                <span id="total-persons-stat-holder" class="stat-holder"></span>
            </div>

            <div class="col-md-2 col-sm-4 col-xs-6 mbottom">
                <h3>{{ trans('analytics.persons_per_reservation') }}</h3>
                <div class="loader stat-loader calign">
                    <img src="{{ asset('/img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}" />
                </div>
                <span id="persons-per-reservation-stat-holder" class="stat-holder"></span> <span class="percent-holder show-after-load" style="display:none;">({{ trans('analytics.average') }})</span>
            </div>
            */
            ?>

            <div class="col-md-15 col-sm-4 col-xs-6 mbottom">
                <h3>{{ trans('analytics.showed_marked') }}</h3>
                <div class="loader stat-loader calign">
                    <img src="{{ asset('/img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}"/>
                </div>
                <span class="stat-icon fa fa-list" title="{{ trans('analytics.reservations') }}"></span>
                <span id="marked-showed-stat-holder" class="stat-holder reservations-stat" style="color:green;"></span>
                <span id="marked-showed-percent-holder" class="percent-holder reservations-stat"></span>
                <br>
                <span class="stat-icon fa fa-user" title="{{ trans('analytics.persons') }}"></span>
                <span id="marked-showed-persons-stat-holder" class="stat-holder persons-stat"
                      style="color:green;"></span> <span id="marked-showed-persons-percent-holder"
                                                         class="percent-holder persons-stat"></span>
            </div>

            <div class="col-md-15 col-sm-4 col-xs-6 mbottom">
                <h3>{{ trans('analytics.showed_not_marked') }}</h3>
                <div class="loader stat-loader calign">
                    <img src="{{ asset('/img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}"/>
                </div>
                <span class="stat-icon fa fa-list" title="{{ trans('analytics.reservations') }}"></span>
                <span id="not-marked-showed-stat-holder" class="stat-holder reservations-stat"
                      style="color:red;"></span> <span id="not-marked-showed-percent-holder"
                                                       class="percent-holder reservations-stat"></span>
                <br>
                <span class="stat-icon fa fa-user" title="{{ trans('analytics.persons') }}"></span>
                <span id="not-marked-showed-persons-stat-holder" class="stat-holder persons-stat"
                      style="color:red;"></span> <span id="not-marked-showed-persons-percent-holder"
                                                       class="percent-holder persons-stat"></span>
            </div>

            <div class="col-md-15 col-sm-4 col-xs-6 mbottom">
                <h3>{{ trans('analytics.walk_ins') }}</h3>
                <div class="loader stat-loader calign">
                    <img src="{{ asset('/img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}"/>
                </div>
                <span class="stat-icon fa fa-list" title="{{ trans('analytics.reservations') }}"></span>
                <span id="walk-ins-stat-holder" class="stat-holder reservations-stat"></span> <span
                        id="walk-ins-percent-holder" class="percent-holder reservations-stat"></span>
                <br>
                <span class="stat-icon fa fa-user" title="{{ trans('analytics.persons') }}"></span>
                <span id="walk-ins-persons-stat-holder" class="stat-holder persons-stat"></span> <span
                        id="walk-ins-persons-percent-holder" class="percent-holder persons-stat"></span>
            </div>

            <div class="col-md-15 col-sm-4 col-xs-6 mbottom">
                <h3>{{ trans('analytics.online_reservations') }}</h3>
                <div class="loader stat-loader calign">
                    <img src="{{ asset('/img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}"/>
                </div>
                <span class="stat-icon fa fa-list" title="{{ trans('analytics.reservations') }}"></span>
                <span id="online-stat-holder" class="stat-holder reservations-stat"></span> <span
                        id="online-percent-holder" class="percent-holder reservations-stat"></span>
                <br>
                <span class="stat-icon fa fa-user" title="{{ trans('analytics.persons') }}"></span>
                <span id="online-persons-stat-holder" class="stat-holder persons-stat"></span> <span
                        id="online-persons-percent-holder" class="percent-holder persons-stat"></span>
            </div>
        </div>
    </div>

    <div class="col-md-12 mboth">
        <div id="chart-container"></div>
    </div>

    <div class="col-md-12 mboth">
        <table class="table table-border table-striped show-after-load" id="data-table" style="display:none;">

            <tr>
                <th>{{ trans('analytics.month') }}</th>
                <th>{{ trans('analytics.normal_reservations') }}</th>
                <th>{{ trans('analytics.persons_per_reservation') }} ({{ trans('analytics.average') }})</th>
                <th>{{ trans('analytics.showed_marked') }}</th>
                <th>{{ trans('analytics.showed_not_marked') }}</th>
                <th>{{ trans('analytics.walk_ins') }}</th>
                <th>{{ trans('analytics.online_reservations') }}</th>
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
            transReservations: '{{ trans('analytics.reservations') }}',
            transPersons: '{{ trans('analytics.persons') }}',
            transReservationsNum: '{{ trans('analytics.reservations_num') }}',
            transPersonsNum: '{{ trans('analytics.persons_num') }}',
        }).dailyStats();
    </script>
@stop
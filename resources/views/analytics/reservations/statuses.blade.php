@extends('analytics.index')

@section('content')
    <div class="col-md-12">
        <div class="filters-section">

            {!! Form::open(['method' => 'POST', 'action' => 'AnalyticsController@setStatusesMonthlyPeriod', 'id' => 'months-form']) !!}

            <div class="col-md-4 col-sm-8">
                <div class="form-group">
                    <select name="months" class="form-control">
                        <option value="3" {{ (!$months || $months == 3) ? 'selected' : '' }}>{{ trans('analytics.x_months', ['x' => 3]) }}</option>
                        <option value="6" {{ $months == 6 ? 'selected' : '' }}>{{ trans('analytics.x_months', ['x' => 6]) }}</option>
                        <option value="12" {{ $months == 12 ? 'selected' : '' }}>{{ trans('analytics.x_months', ['x' => 12]) }}</option>
                    </select>
                </div>
            </div>

            {!! Form::close() !!}
            <div class="clear"></div>
        </div>
    </div>

    <div class="col-md-12 mbottom">
        <p class="alert alert-info">{{ trans('analytics.data_until_yesterday_msg', ['date' => date("Y-m-d", strtotime("yesterday"))]) }}</p>
    </div>

    <div class="col-md-12 pnone mbottom">
        <div class="stats-section">
            <div class="col-md-15 col-sm-3 col-xs-6 mbottom">
                <h3>{{ trans('analytics.total_reservations') }}</h3>
                <div class="loader stat-loader calign">
                    <img src="{{ asset('/img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}"/>
                </div>
                <span class="stat-icon fa fa-list" title="{{ trans('analytics.reservations') }}"></span>
                <span id="total-reservations-stat-holder" class="stat-holder" style="color:#000;"></span>
                <br>
                <span class="stat-icon fa fa-user" title="{{ trans('analytics.persons') }}"></span>
                <span id="total-persons-stat-holder" class="stat-holder" style="color:#000;"></span>
            </div>

            <div class="col-md-15 col-sm-3 col-xs-6 mbottom">
                <h3>{{ trans('analytics.normal_reservations') }}</h3>
                <div class="loader stat-loader calign">
                    <img src="{{ asset('/img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}"/>
                </div>
                <span class="stat-icon fa fa-list" title="{{ trans('analytics.reservations') }}"></span>
                <span id="normal-reservations-stat-holder" class="stat-holder" style="color:#00b200;"></span> <span
                        id="normal-reservations-percent-holder" class="percent-holder"></span>
                <br>
                <span class="stat-icon fa fa-user" title="{{ trans('analytics.persons') }}"></span>
                <span id="normal-persons-stat-holder" class="stat-holder" style="color:#00b200;"></span> <span
                        id="normal-persons-percent-holder" class="percent-holder"></span>
            </div>

            <div class="col-md-15 col-sm-3 col-xs-6 mbottom">
                <h3>{{ trans('analytics.cancelled_reservations') }}</h3>
                <div class="loader stat-loader calign">
                    <img src="{{ asset('/img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}"/>
                </div>
                <span class="stat-icon fa fa-list" title="{{ trans('analytics.reservations') }}"></span>
                <span id="cancelled-reservations-stat-holder" class="stat-holder" style="color:#ff0000;"></span> <span
                        id="cancelled-reservations-percent-holder" class="percent-holder"></span>
                <br>
                <span class="stat-icon fa fa-user" title="{{ trans('analytics.persons') }}"></span>
                <span id="cancelled-persons-stat-holder" class="stat-holder" style="color:#ff0000;"></span> <span
                        id="cancelled-persons-percent-holder" class="percent-holder"></span>
            </div>

            <div class="col-md-15 col-sm-3 col-xs-6 mbottom">
                <h3>{{ trans('analytics.no_show_reservations') }}</h3>
                <div class="loader stat-loader calign">
                    <img src="{{ asset('/img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}"/>
                </div>
                <span class="stat-icon fa fa-list" title="{{ trans('analytics.reservations') }}"></span>
                <span id="noshow-reservations-stat-holder" class="stat-holder" style="color:#9d0000;"></span> <span
                        id="noshow-reservations-percent-holder" class="percent-holder"></span>
                <br>
                <span class="stat-icon fa fa-user" title="{{ trans('analytics.persons') }}"></span>
                <span id="noshow-persons-stat-holder" class="stat-holder" style="color:#9d0000;"></span> <span
                        id="noshow-persons-percent-holder" class="percent-holder"></span>
            </div>

            <div class="col-md-15 col-sm-3 col-xs-6 mbottom">
                <h3>{{ trans('analytics.waiting_list_reservations') }}</h3>
                <div class="loader stat-loader calign">
                    <img src="{{ asset('/img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}"/>
                </div>
                <span class="stat-icon fa fa-list" title="{{ trans('analytics.reservations') }}"></span>
                <span id="waiting-reservations-stat-holder" class="stat-holder" style="color:#fcb800;"></span> <span
                        id="waiting-reservations-percent-holder" class="percent-holder"></span>
                <br>
                <span class="stat-icon fa fa-user" title="{{ trans('analytics.persons') }}"></span>
                <span id="waiting-persons-stat-holder" class="stat-holder" style="color:#fcb800;"></span> <span
                        id="waiting-persons-percent-holder" class="percent-holder"></span>
            </div>

        </div>
    </div>

    <div class="col-md-12 mboth">
        <div class="chart-tabs show-after-load" style="display:none;">
            <a href="javascript:;" class="chart-tab nav-tab-maroon"
               data-report="reservations">{{ trans('analytics.reservations') }}</a>
            <a href="javascript:;" class="chart-tab nav-tab-maroon active"
               data-report="persons">{{ trans('analytics.persons') }}</a>
        </div>

        <div class="chart-container" data-report="reservations"></div>
        <div class="chart-container active" data-report="persons"></div>
    </div>

    <div class="col-md-12 mboth">
        <table class="table table-border table-striped show-after-load" id="data-table" style="display:none;">

            <tr>
                <th>{{ trans('analytics.month') }}</th>
                <th>{{ trans('analytics.total_reservations') }}</th>
                <th>{{ trans('analytics.normal_reservations') }}</th>
                <th>{{ trans('analytics.cancelled_reservations') }}</th>
                <th>{{ trans('analytics.no_show_reservations') }}</th>
                <th>{{ trans('analytics.waiting_list_reservations') }}</th>
            </tr>

        </table>
    </div>
@stop

@section('script')
    <script>
        new Analytic({
            months: '{{ $months }}',
            decimalPoint: '{{ \App\Config::$decimal_point }}',
            transReservations: '{{ trans('analytics.reservations') }}',
            transPersons: '{{ trans('analytics.persons') }}',
            transNormalReservations: '{{ trans('analytics.normal_reservations') }}',
            transCanceledReservations: '{{ trans('analytics.cancelled_reservations') }}',
            transNoReservations: '{{ trans('analytics.no_show_reservations') }}',
            transWaitingReservations: '{{ trans('analytics.waiting_list_reservations') }}',
            transNormal: '{{ trans('analytics.normal') }}',
            transCanceled: '{{ trans('analytics.cancelled') }}',
        }).statusStats();
    </script>
@stop
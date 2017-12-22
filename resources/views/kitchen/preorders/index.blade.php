@extends('kitchen.master')

@section('header-print')
    @include('components.print_button')
@stop

@section('nav')

    <li class="sidebar-nav__menu-item current active">
        <a href="{{ action('PreordersController@index') }}" class="sidebar-nav__menu-item--link">
            {{ trans('kitchen.preorders') }}
        </a>

        <svg class="sidebar-nav__menu-item--tail">
            <use xlink:href="#icon-tail"></use>
        </svg>
    </li>

@stop

@section('content')

    <div class="kitchen">
        <div class="kitchen-header">

            @include('components.tools')

        </div>

        <!--  TODO delete ? -->
        <div class="offdays-view">

        </div>

        <div class="kitchen-content">

            <div class="table">
                <table class="display table-reservations js-table-reservations" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Person</th>
                        <th>Name</th>
                        <th>Landline</th>
                        <th>Time</th>
                        <th>Tables</th>
                        <th>Status</th>
                        <th>Left</th>
                        <th>Manager</th>
                        <th>Event Type</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="table__header">
                Waiting List
            </div>

            <div class="table">
                <table class="table-waiting js-table-waiting" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Person</th>
                        <th>Name</th>
                        <th>Landline</th>
                        <th>Time</th>
                        <th>Tables</th>
                        <th>Status</th>
                        <th>Left</th>
                        <th>Manager</th>
                        <th>Event Type</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="table__header">
                Canceled
            </div>

            <div class="table">
                <table class="table-canceled js-table-canceled" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Person</th>
                        <th>Name</th>
                        <th>Landline</th>
                        <th>Time</th>
                        <th>Tables</th>
                        <th>Status</th>
                        <th>Left</th>
                        <th>Manager</th>
                        <th>Event Type</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="table__header">
                No-show
            </div>

            <div class="table">
                <table class="table-noshow js-table-noshow" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Person</th>
                        <th>Name</th>
                        <th>Landline</th>
                        <th>Time</th>
                        <th>Tables</th>
                        <th>Status</th>
                        <th>Left</th>
                        <th>Manager</th>
                        <th>Event Type</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>



@stop

@section('helpers')

    @parent

    <!----- CHOOSE TABLE MODAL ------>
    @component('components.modal', ['class' => 'tableM__modal js-tableM__modal'])

        <div class="tableM__modal--title js-tableM__modal--title">
            {{--            {{ trans('restaurant.choose_table') }}--}}
        </div>

        <ul class="tableM__modal-header">
            @foreach($sections as $sectionId => $sectionName)
                <li class="section-tab tableM__modal-header-item js-tableM__modal-header-item"
                    data-section-id="{{ $sectionId }}">
                    <a data-toggle="tab" class="tableM__modal-header-item--link"
                       href="#section-{{ $sectionId }}">{{ $sectionName }}</a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content tableM__modal-content js-tableM__modal-content">
            @foreach($sections as $sectionId => $sectionName)
                <div id="section-{{ $sectionId }}" data-section-id="{{ $sectionId }}"
                     class="section-content tab-pane fade">
                    <div class="section-map"></div>
                </div>
            @endforeach
        </div>

    @endcomponent

@stop

@section('script')

    {{--<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="text/javascript"></script>--}}
    {{--<script src="https://cdn.datatables.net/plug-ins/1.10.12/api/fnFilterAll.js" type="text/javascript"></script>--}}

    <script>
 /*       var reservationsTable = $('.reservations-table').DataTable({
            "paging": false,
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                },
                null,
                null,
                null,
                {"orderDataType": "dom-text-numeric", "type": "numeric"},
                null,
                null,
                null,
                null,
                null,
                null
            ],
            "info": false,
            "order": [[3, "asc"]],
            "oLanguage": {
                "sEmptyTable": ""
            }
        });

        var waitingTable = $(".waiting-table").DataTable({
            "paging": false,
            "info": false,
            "order": [[4, "asc"]],
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                },
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null
            ],
            "oLanguage": {
                "sEmptyTable": ""
            }
        });

        var cancelledTable = $('.cancelled-table').DataTable({
            "paging": false,
            "info": false,
            "order": [[3, "asc"]],
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                },
                null,
                null,
                null,
                null,
                null,
                null,
                null
            ],
            "oLanguage": {
                "sEmptyTable": ""
            }
        });

        var noshowTable = $('.noshow-table').DataTable({
            "paging": false,
            "info": false,
            "order": [[3, "asc"]],
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                },
                null,
                null,
                null,
                null,
                null,
                null,
                null
            ],
            "oLanguage": {
                "sEmptyTable": ""
            }
        });*/

        function insertRecord(target, data) {

            var reservationId = data['id'];
            var time = data['time'];
            //var dateTime = data['time']

            var tableId = (data['table_id'] != 0 && data['table_id'] != null && data['table_id'] != 'null') ? (data['table_id']) : '';

            var resultTr = null;
            var myTable = null;

            var notes = data['description'] || null;
            if (!notes || notes == 'null') {
                notes = '';
            }

            var name = '';
            var phone = '';

            if (data['client']) {
                var stickyNote = data['client']['sticky_note'];
                if (stickyNote) {
                    notes = '<strong>{{ trans('reception.sticky') }}:</strong> ' + stickyNote + '<br>' + notes;
                }

                name = data['client']['last_name'];

                if (data['client']['first_name']) {
                    name = data['client']['first_name'] + ' ' + name;
                }
            }
            else {
                if (data['name']) {
                    name = data['name'];
                }
            }

            preorders = '';
            if (data['preorders_summary']) {
                preorders = data['preorders_summary'];
            }

            var rowData = [
                '<strong>' + name + '</strong>',
                '<strong>' + data['persons_num'] + '</strong>',
                '<span class="time-holder" data-time="' + time + '"><strong>' + time.substr(0, 5) + '</strong></span>',
            ];

            var clientStatuses = JSON.parse('{!! json_encode($clientStatuses) !!}');
            var eventTypes = JSON.parse('{!! json_encode($eventTypes) !!}');
            var offerStatuses = JSON.parse('{!! json_encode($offerStatuses) !!}');

            var iconsHtml = '';
            if (data['client']) {
                iconsHtml += ' <span title="' + clientStatuses[data['client']['status_id']]['text'] + '"><img src="/img/icons/32/client-status/' + clientStatuses[data['client']['status_id']]['name'] + '.png" style="height:20px;" alt="' + clientStatuses[data['client']['status_id']]['text'] + '" /></span>';
            }
            if (data['event_type_id'] > 1) {
                iconsHtml += ' <span title="' + eventTypes[data['event_type_id']]['text'] + '"><img src="/img/icons/32/event-types/' + eventTypes[data['event_type_id']]['name'] + '.png" style="height:20px;" alt="' + eventTypes[data['event_type_id']]['name'] + '" /></span>';
            }
            if (data['offer_status_id'] > 1) {
                iconsHtml += ' <span title="' + offerStatuses[data['offer_status_id']]['text'] + '"><img src="/img/icons/32/offer-status/' + offerStatuses[data['offer_status_id']]['name'] + '.png" style="height:20px;" alt="' + offerStatuses[data['offer_status_id']]['name'] + '" /></span>';
            }
            if (data['source'] == 'online') {
                var ref = '';
                if (data['ref']) {
                    ref = ' (' + data['ref'] + ')';
                }
                iconsHtml += ' <span title="{{ trans('general.online') }}' + ref + '"><img src="/img/icons/32/sources/online.png" style="height:20px;" alt="{{  trans('reception.online') }}" /></span>';
            }
            if (data['is_walkin'] != 0 && data['is_walkin'] != false) {
                iconsHtml += ' <span title="{{ trans('reception.walk_in') }}"><img src="/img/icons/32/walk-in.png" style="height:20px;" alt="{{  trans('reception.walk_in') }}" /></span>';
            }
            if (data['has_preorders'] != 0 && data['has_preorders'] != false) {
                iconsHtml += ' <span title="{{ trans('reception.has_preorders') }}"><img src="/img/icons/32/preorder.png" style="height:20px;" alt="{{  trans('reception.has_preorders') }}" /></span>';
            }


            var actionsHtml = '';
            actionsHtml += '<span class="hidden-print"><a href="{{ url('kitchen/preorders') }}/' + reservationId + '/menu" target="_blank"><span class="fa fa-external-link"></span></a>';
            if (data['has_preorders'] != 0 && data['has_preorders'] != false) {
                actionsHtml += '&nbsp;&nbsp;<a href="{{ action('PreordersController@index') }}/' + reservationId + '/show"><span class="fa fa-edit"></span></a></span>';
            }

            //var expandControlHtml = '<a href="javascript:;" onclick="toggleSummary($(this));"><span class="expand-control"></span></a>';
            var expandControlHtml = '';

            if (preorders != '') {
                expandControlHtml = '<a href="javascript:;" onclick="toggleSummary($(this));"><span class="expand-control fa fa-plus green"></span></a>';
            }

            rowData.unshift(expandControlHtml);

            if (target == 'active') {

                var showedSpan = data['showed'] == 1 ? '<span class="fa fa-check green"></span>' : '';
                var leftSpan = data['left'] == 1 ? '<span class="fa fa-check green"></span>' : '';

                rowData = rowData.concat([
                    tableId,
                    showedSpan,
                    leftSpan,
                    data['updated_by'],
                    iconsHtml,
                    actionsHtml,
                    '<span style="display:none;" data-reservation-id="' + reservationId + '" class="loading-holder">L</span>'
                ]);

                var tr = reservationsTable.row.add(rowData).draw().node();

                $(tr).addClass('active-reservation-row');
                $(tr).attr('id', 'reservation-tr-' + reservationId);

                if (data['showed'] == 0 && data['left'] == 0) {
                    $(tr).addClass('pending');
                }

                resultTr = $(tr);
                myTable = reservationsTable;
            }
            else if (target == 'cancelled') {

                if (!window.cancelledSection) {
                    $(".cancelled-section").show();
                    window.cancelledSection = true;
                }

                rowData = rowData.concat([
                    data['updated_by'],
                    iconsHtml,
                    actionsHtml,
                    '<span style="display:none;" data-reservation-id="' + reservationId + '" class="loading-holder">L</span>'
                ]);

                var tr = cancelledTable.row.add(rowData).draw().node();
                $(tr).addClass('cancelled-reservation-row');
                $(tr).attr('id', 'reservation-tr-' + reservationId);

                resultTr = $(tr);
                myTable = cancelledTable;
            }
            else if (target == 'noshow') {
                if (!window.noshowSection) {
                    $(".noshow-section").show();
                    window.noshowSection = true;
                }

                rowData = rowData.concat([
                    data['updated_by'],
                    iconsHtml,
                    actionsHtml,
                    '<span style="display:none;" data-reservation-id="' + reservationId + '" class="loading-holder">L</span>'
                ]);

                var tr = noshowTable.row.add(rowData).draw().node();

                $(tr).addClass('noshow-reservation-row');
                $(tr).attr('id', 'reservation-tr-' + reservationId);

                resultTr = $(tr);
                myTable = noshowTable;
            }
            else if (target == 'waiting') {


                if (!window.waitingSection) {
                    $(".waiting-section").show();
                    window.waitingSection = true;
                }

                rowData = rowData.concat([
                    data['created_at'],
                    data['updated_by'],
                    iconsHtml,
                    actionsHtml,
                    '<span style="display:none;" data-reservation-id="' + reservationId + '" class="loading-holder">L</span>'
                ]);

                var tr = waitingTable.row.add(rowData).draw().node();

                $(tr).addClass('waiting-reservation-row');
                $(tr).attr('id', 'reservation-tr-' + reservationId);

                resultTr = $(tr);
                myTable = waitingTable;
            }

            if (preorders && preorders.length > 0) {

                $(tr).addClass('has-preorders');

                preorders = '<div class="preorder-description">' + preorders + '</div>';

                var row = myTable.row(resultTr);
                row.child(preorders, 'summary-row').hide();

                var child = row.child();

                child.css({
                    'line-height': '28px',
                    'background': '#f9f9f9',
                });
            }

            return resultTr;


        }

        var api = 'preorders';
    </script>

    @include('partials.reservations_js')

    <script>
        function toggleSummary($el) {

            var $tr = $el.closest('tr');

            var table = null;
            if ($tr.hasClass('active-reservation-row')) {
                table = reservationsTable;
            }
            else if ($tr.hasClass('noshow-reservation-row')) {
                table = noshowTable;
            }
            else if ($tr.hasClass('cancelled-reservation-row')) {
                table = cancelledTable;
            }
            else if ($tr.hasClass('waiting-reservation-row')) {
                table = waitingTable;
            }

            var $control = $el.find('.expand-control');

            var row = table.row($tr);

            if (row.child.isShown()) {
                row.child.hide();

                $tr.removeClass('shown');

                $control.removeClass('fa-minus');
                $control.removeClass('red');

                $control.addClass('fa-plus');
                $control.addClass('green');
            }
            else {
                row.child.show();
                $tr.addClass('shown');

                $control.removeClass('fa-plus');
                $control.removeClass('green');

                $control.addClass('fa-minus');
                $control.addClass('red');
            }
        }

        function expandAll() {

            var tables = [reservationsTable, cancelledTable, noshowTable, waitingTable];

            for (var i = 0; i < tables.length; i++) {
                var table = tables[i];
                table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    var $row = $(this.node());
                    if (this.child() && !this.child.isShown()) {
                        $row.find('.details-control a').click();
                    }
                });
            }
        }

        function collapseAll() {

            var tables = [reservationsTable, cancelledTable, noshowTable, waitingTable];

            for (var i = 0; i < tables.length; i++) {
                var table = tables[i];
                table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    var $row = $(this.node());
                    if (this.child() && this.child.isShown()) {
                        $row.find('.details-control a').click();
                    }
                });
            }
        }

        function filterPreorders(checked) {

            if (checked) {
                $("tbody tr[role='row']:not(.has-preorders)").fadeOut(500);
            }
            else {
                $("tbody tr[role='row']:not(.has-preorders)").fadeIn(800);
            }
        }

        $(function () {

            new Kitchen({
                language: '{!! \App\Config::$language !!}',
                dayStart: '{!! $dayStart !!}',
                nightEnd: '{!! $nightEnd !!}',
                noReservationsMsg: '{!! trans('reception.no_reservations_found') !!}',
            }).init();



            $("input[name='preorders_only']").change(function () {

                filterPreorders(this.checked);
            });
        });
    </script>
@stop
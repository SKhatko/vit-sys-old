@extends('reception.index')

@section('content')

    <div class="reception">
        <div class="reception-header">

            @include('components.tools')

        </div>

        <!--  TODO delete ? -->
        <div class="offdays-view">

        </div>

        <div class="reception-content">

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

        <div class="tableM__modal-header">
            <ul class="tableM__modal-header--list owl-carousel">
                @foreach(array_slice($sections, 0 , 4) as $sectionId => $sectionName)
                    <li class="section-tab tableM__modal-header--list-item js-tableM__modal-header--list-item"
                        data-section-id="{{ $sectionId }}">
                        <a data-toggle="tab"
                           class="tableM__modal-header--list-item--link"
                           href="#section-{{ $sectionId }}"
                           title="{{ $sectionName }}"
                        >
                            {{ $sectionName }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <a class="tableM__modal-header--button js-tableM__modal-header--button">
                <svg class="tableM__modal-header--button-icon js-tableM__modal-header--button-icon">
                    <use xlink:href="#arrow-down"></use>
                </svg>
            </a>
        </div>

        <div class="tab-content tableM__modal-content js-tableM__modal-content">
            @foreach($sections as $sectionId => $sectionName)
                <div id="section-{{ $sectionId }}" data-section-id="{{ $sectionId }}"
                     class="section-content tab-pane fade">
                    <div class="section-map"></div>
                </div>
            @endforeach
        </div>

    @endcomponent

    <!----- DELETE RES MODAL ------>
    @component('components.modal', ['class' => 'reception__modal-delete js-reception__modal-delete'])

        {{ Form::open(['method' => 'DELETE', 'action' => 'ReservationsController@index']) }}

        <div class="reception__modal-delete--title">
            {{ trans('reception.delete_reservation') }}
        </div>

        <div class="reception__modal-delete--content">
            {{ trans('reception.delete_reservation_confirmation_msg') }}
        </div>

        <div class="reception__modal-delete">

        </div>

        {!! Form::label('user', trans('reception.action_by_name'), ['class' => 'label']) !!}
        {!! Form::text('user', NULL, ['class' => 'input', 'required', 'autocomplete' => 'off']) !!}

        {{ Form::button(trans('general.confirm'), ['type'=>'submit', 'class' => 'reception__modal-delete--submit']) }}

        {{ Form::close() }}

    @endcomponent

    <!----- CANCEL RES MODAL ------>
    @include('partials.modal_top', ['modalId' => 'change-res-dialog'])

    <form method="post" action="" id="change-res-form">
        <input type="hidden" name="res_id" id="change-res-id"/>
        <input type="hidden" name="status_value" id="change-res-status"/>

        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h3 id="change-res-heading">{{ trans('reception.cancel_reservation') }}</h3>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <!--
                <p class="alert alert-warning" id="change-res-msg">

                </p>
                -->
            </div>

            <div class="form-group">
                {!! Form::label('user', trans('reception.action_by_name').' *') !!}
                {!! Form::text('user', NULL, ['class' => 'form-control', 'id' => 'change-res-user', 'required', 'autocomplete' => 'off']) !!}
            </div>
        </div>

        <div class="modal-footer">
            {!! Form::submit(trans('general.submit'), ['class' => 'btn btn-primary', 'id' => 'change-res-btn']) !!}
        </div>

    </form>

    @include('partials.modal_bottom')

@stop

@section('script')

    {{--<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="text/javascript"></script>--}}
    {{--<script src="https://cdn.datatables.net/plug-ins/1.10.12/api/fnFilterAll.js" type="text/javascript"></script>--}}

    <script>

        /** Table Change functionality **/

        function tableFocus(id, value) {
            window.lastTableId = id;
            window.lastTable = value;
        }

        function tableBlur(id, value) {
            if (window.lastTableId == id && window.lastTable == value) {
                //no change
            }
            else {
                var data = 'table=' + encodeURIComponent(value);
                sendAjax('update-table', id, data);
            }
        }

        var shiftSwap = '{!! \App\Config::$day_end !!}';
        var noTablePlanErrorMsg = '{!! trans('reception.no_table_plan_found_msg') !!}';


        /** Change Reservation Functions below **/
        function statusSelectChange(el) {
            var $thisSel = jQuery(el);
            var reservationId = $thisSel.attr('data-reservation-id');
            var value = $thisSel.val();
            var oldValue = $thisSel.attr('data-reservation-status');
            $thisSel.val(oldValue); //Keep old value until move is complete (especially on modal close)
            changeStatus(reservationId, value);
        }

        function clearChangeReservationDialog() {
            $("#change-res-heading").text("");
            $("#change-res-id").val("");
            $("#change-res-status").val("");
            $("#change-res-user").val("");
            $("#change-res-msg").html("");
            $("#change-res-btns a").hide();
        }

        function changeStatus(id, value) {
            $("#change-res-form h3").text("{{ trans('reception.update_reservation_status') }}");
            $("#change-res-id").val(id);
            $("#change-res-status").val(value);
            $("#change-res-msg").html("{{ trans('reception.insert_name_to_mark_as') }} <strong>" + value + "</strong>.");
            $("#change-res-dialog").modal('show');
        }

        function submitStatus() {
            var user = $("#change-res-user").val();
            var resId = $("#change-res-id").val();
            var value = $("#change-res-status").val();
            var data = 'user=' + user + '&value=' + value;
            sendAjax('set-status', resId, data, statusSubmitted);
            $("#change-res-dialog").modal('hide');
        }

        function statusSubmitted(id, data) {
            clearChangeReservationDialog();
            setTimeout(function () {
                resetRefresher();
            }, 1000);
        }

        /*********************/


        function showed(el) {
            var reservationId = el.attr('data-reservation-id');

            if (el.is(":checked")) {
                //$("#reservation-tr-"+reservationId).css("background", "#8fffa4");
                $("#left-" + reservationId).prop('disabled', false);
                //remove pending class
                $("#reservation-tr-" + reservationId).removeClass('pending');
                sendAjax('mark-showed', reservationId);

            }
            else {
                var $tr = $("#reservation-tr-" + reservationId);
                //$tr.css("background", "transparent");
                $("#left-" + reservationId).prop('checked', false);
                $("#left-" + reservationId).prop('disabled', true);
                $tr.addClass('pending');
                sendAjax('unmark-showed', reservationId);

            }
            setColors();
        }

        function left(el) {
            var reservationId = el.attr('data-reservation-id');
            if (el.is(":checked")) {
                //$("#reservation-tr-"+reservationId).css("background", "#c6ffd1");
                sendAjax('mark-left', reservationId);
            }
            else {
                //$("#reservation-tr-"+reservationId).css("background", "#8fffa4");
                sendAjax('unmark-left', reservationId);
            }
            setColors();
        }

        function sendAjax(funcName, id, data, callBack) {

            activateLoading(id);

            $.ajax({
                method: "GET",
                data: data,
                url: window.location.protocol + "//" + window.location.hostname + "/reception/ajax/reservations/" + funcName + "/" + id
            }).done(function (data) {
                stopLoading(id);

                if (callBack) {
                    callBack(id, data);
                }
                //performedFunction(funcName, id, true);
            }).fail(function (xhr) {
                abortLoading(id);
                alert('{{ trans('general.server_communication_error_msg') }}');
                $("#reservation-tr-" + id).css("background", "red");
                location.reload();
            });
        }

        function deleteReservation(id) {
            $("#delete-res-id").val(id);
            $("#delete-res-user").val("");
            $("#delete-res-dialog").modal('show');
        }

        function getTableById(id) {
            var $row = $("#reservation-tr-" + id);
            if ($row.hasClass('active-reservation-row')) {
                return reservationsTable;
            }
            if ($row.hasClass('cancelled-reservation-row')) {
                return cancelledTable;
            }
            if ($row.hasClass('noshow-reservation-row')) {
                return noshowTable;
            }
            if ($row.hasClass('waiting-reservation-row')) {
                return waitingTable;
            }
        }

        function reservationDeleted(id, data) {
            alert('{{ trans('reception.delete_reservation_success_msg') }}');
            var myTable = getTableById(id);
            myTable.row("#reservation-tr-" + id).remove().draw();
            resetRefresher();
        }

        function insertRecord(target, data) {

            var reservationId = data['id'];
            var time = data['time'];
            //var dateTime = data['time']

            var tableId = (data['table_id'] != 0 && data['table_id'] != null && data['table_id'] != 'null') ? (data['table_id']) : '';
            var tableHtml = '<input class="table-table-input" data-reservation-id="' + reservationId + '" value="' + tableId + '" style="width:40px;" onfocus="tableFocus(' + reservationId + ', this.value);" onblur="tableBlur(' + reservationId + ', this.value);" /> <a href="javascript:;" onclick="openTableViewModal(' + reservationId + ');" title="{{ trans('restaurant.tables_view') }}"><img src="{{ asset('img/maroon-table.png') }}" alt="T" width="18" class="choose-table-icon" /></a>';

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

                if (data['client']['mobile']) {
                    phone = data['client']['mobile'];
                    if (data['client']['phone']) {
                        phone = '<span title="' + phone + ' / ' + data['client']['phone'] + '">' + phone + ' <strong>*</strong></span>';
                    }
                }
                else if (data['client']['phone']) {
                    phone = data['client']['phone'];
                }
            }
            else {
                if (data['name']) {
                    name = data['name'];
                }
            }

            var company = '';
            if (data['company'] && data['company']['name']) {
                company = data['company']['name'];
            }

            var rowData = [
                '<strong>' + name + '</strong>',
                phone,
                company,
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

            if (target == 'active') {
                var showedCheckbox = '<input type="checkbox" data-reservation-id="' + reservationId + '" id="showed-' + reservationId + '" class="showed-input" onclick="showed($(this));"';
                if (data['showed'] == 1) {
                    showedCheckbox += ' checked';
                }
                showedCheckbox += ' />';


                var leftCheckbox = '<input type="checkbox" data-reservation-id="' + reservationId + '" id="left-' + reservationId + '" class="left-input" onclick="left($(this));"';
                if (data['left'] == 1) {

                    leftCheckbox += ' checked';
                }
                leftCheckbox += ' />';


                var actionsHtml = '<select style="width:90px;" class="reservation-status-sel" data-reservation-id="' + reservationId + '" onchange="statusSelectChange(this);" data-reservation-status="active">';
                actionsHtml += '<option value="active" selected>{{ trans('reception.active') }}</option>';
                actionsHtml += '<option value="cancelled">{{ trans('reception.cancel') }}</option>';
                actionsHtml += '<option value="noshow">{{ trans('reception.noshow') }}</option>';
                actionsHtml += '<option value="waiting">{{ trans('reception.waiting') }}</option>';
                actionsHtml += '</select> ';

                actionsHtml += '<a href="{{ action('ReservationsController@index') }}/' + reservationId + '/edit" title="{{ trans('general.edit') }}" class="reservation-edit-icon">';
                actionsHtml += '<span class="fa fa-edit"></span> ';
                actionsHtml += '</a>';

                actionsHtml += ' <a href="javascript:;" onclick="deleteReservation(' + reservationId + ');" title="{{ trans('general.delete') }}">';
                actionsHtml += '<span class="fa fa-trash-o"></span>';
                actionsHtml += '</a>';

                rowData = rowData.concat([
                    tableHtml,
                    showedCheckbox,
                    leftCheckbox,
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

                var actionsHtml = '<a href="javascript:;" onclick="changeStatus(' + reservationId + ', \'active\');" title="{{ trans('reception.restore') }}">';
                actionsHtml += '<span class="fa fa-undo"></span>';
                actionsHtml += '</a> ';
                actionsHtml += '<a href="{{ action('ReservationsController@index') }}/' + reservationId + '/edit" title="{{ trans('general.edit') }}" class="reservation-edit-icon">';
                actionsHtml += '<span class="fa fa-edit"></span>';
                actionsHtml += '</a>';

                actionsHtml += ' <a href="javascript:;" onclick="deleteReservation(' + reservationId + ');" title="{{ trans('general.delete') }}">';
                actionsHtml += '<span class="fa fa-trash-o"></span>';
                actionsHtml += '</a>';

                rowData = rowData.concat([
                    tableHtml,
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

                var actionsHtml = '<a href="javascript:;" onclick="changeStatus(' + reservationId + ', \'active\');" title="{{ trans('reception.restore') }}">';
                actionsHtml += '<span class="fa fa-undo"></span>';
                actionsHtml += '</a> ';
                actionsHtml += '<a href="{{ action('ReservationsController@index') }}/' + reservationId + '/edit" title="{{ trans('general.edit') }}" class="reservation-edit-icon">';
                actionsHtml += '<span class="fa fa-edit"></span>';
                actionsHtml += '</a>';

                actionsHtml += ' <a href="javascript:;" onclick="deleteReservation(' + reservationId + ');" title="{{ trans('general.delete') }}">';
                actionsHtml += '<span class="fa fa-trash-o"></span>';
                actionsHtml += '</a>';

                rowData = rowData.concat([
                    tableHtml,
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

                var actionsHtml = '<a href="javascript:;" onclick="changeStatus(' + reservationId + ', \'active\');" title="{{ trans('reception.restore') }}">';
                actionsHtml += '<span class="fa fa-undo"></span>';
                actionsHtml += '</a> ';
                actionsHtml += '<a href="{{ action('ReservationsController@index') }}/' + reservationId + '/edit" title="{{ trans('general.edit') }}" class="reservation-edit-icon">';
                actionsHtml += '<span class="fa fa-edit"></span>';
                actionsHtml += '</a>';

                actionsHtml += ' <a href="javascript:;" onclick="deleteReservation(' + reservationId + ');" title="{{ trans('general.delete') }}">';
                actionsHtml += '<span class="fa fa-trash-o"></span>';
                actionsHtml += '</a>';

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

            if (notes && notes.length > 0) {
                myTable.row(resultTr).child(notes).show();

                var $tr = $(myTable.row(resultTr).node());

                var child = myTable.row(resultTr).child();
                child.css({
                    //'background-color' : '#e8e8e8',
                    'text-align': 'center'
                });
            }

            return resultTr;
        }

        function resetDatePicker() {

            var badNightDates = JSON.parse('{!! json_encode($badNightDates) !!}');
            var badDayDates = JSON.parse('{!! json_encode($badDayDates) !!}');

            $('.filter-date-control').datepicker('remove');

            $('.filter-date-control').datepicker({
                language: '{{ \App\Config::$language }}',
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                beforeShowDay: function (date) {

                    let formattedDate = formatDate(date);
                    let shift = getTimeOfDay();
                    if (shift === 'night') {
                        if (badNightDates[formattedDate] == 'closed') {
                            return {classes: 'closed bold'};
                        }
                        else if (badNightDates[formattedDate] == 'disabled') {
                            return {classes: 'sdisabled'};
                        }
                        else {
                            return {classes: 'bold'};
                        }

                    } else if (shift === 'day') {
                        if (badDayDates[formattedDate] == 'closed') {
                            return {classes: 'closed bold'};
                        }
                        else if (badDayDates[formattedDate] == 'disabled') {
                            return {classes: 'sdisabled'};
                        }
                        else {
                            return {classes: 'bold'};
                        }
                    }
                }
            }).on('changeDate', function (e) {

                $("#reservation-create-link").attr("href", "{{ url('reception/reservations/create') }}/" + e.format());
                $("#print-date").val(e.format());
                $("#lights-date-input").val(e.format());
            });


        }


    </script>
    {{--    @include('partials.reservations_js')--}}


    <script>
        $(function () {

            new Reception({
                language: '{!! \App\Config::$language  !!}',
                dayStart: '{!! $dayStart !!}',
                nightEnd: '{!! $nightEnd !!}',
                noReservationsMsg: '{!! trans('reception.no_reservations_found') !!}',
            }).init();


            $("#change-res-form").submit(function (e) {
                submitStatus();
                e.preventDefault();
                return false;
            });


            $("#delete-res-form").submit(function (e) {
                var id = $("#delete-res-id").val();
                var user = $("#delete-res-user").val();
                var data = 'user=' + user;
                sendAjax('delete', id, data, reservationDeleted);
                $("#delete-res-dialog").modal('hide');

                e.preventDefault();
                return false;
            });


            $('.js-tools-lights__modal').on('hidden.bs.modal', function () {
                lightsModalOpened = false;
            });


        });
    </script>

@stop

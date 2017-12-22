<script>

    $.fn.dataTable.ext.order['dom-text-numeric'] = function (settings, col) {
        return this.api().column(col, {order: 'index'}).nodes().map(function (td, i) {
            return Number($('input', td).val().replace(/[^0-9\.]/g, ''));
        });
    };


    window.lastTime = ""; //to request updated records (since last time)
    window.lastTable = ""; //to track table changes
    window.lastTableId = ""; //to track table changes


    function activateLoading(id) {
        $(".loading-holder[data-reservation-id='" + id + "']").show();
    }

    function stopLoading(id) {
        $(".loading-holder[data-reservation-id='" + id + "']").hide();
    }

    function abortLoading(id) {
        $(".loading-holder[data-reservation-id='" + id + "']").html('ERROR').show();
    }

    function handleOffdays(timeOfDay, dayChanged, nightChanged) {

        $(".offdays-view").html("");

        if (timeOfDay == 'all' || timeOfDay == 'day') {
            if (dayChanged == 2) {
                $(".offdays-view").append('<p class="alert alert-danger">{{ trans('reception.day_shift_disabled_for_date') }}</p>');
            }
            else if (dayChanged == 1) {
                $(".offdays-view").append('<p class="alert alert-warning">{{ trans('reception.day_shift_changed_for_date') }}</p>');
            }
        }
        if (timeOfDay == 'all' || timeOfDay == 'night') {
            if (nightChanged == 2) {
                $(".offdays-view").append('<p class="alert alert-danger">{{ trans('reception.night_shift_disabled_for_date') }}</p>');
            }
            else if (nightChanged == 1) {
                $(".offdays-view").append('<p class="alert alert-warning">{{ trans('reception.night_shift_changed_for_date') }}</p>');
            }
        }

    }

    var lightsModalOpened = false;

    //sets colors for showed rows (light green(showed) or dark green(showed and left))
    //also returns colors to white for other rows (after update)



    function getReservationTableById(reservationId) {

        if ($("#reservation-tr-" + reservationId).length) {

            var $reservationTr = $("#reservation-tr-" + reservationId);

            if ($reservationTr.hasClass('active-reservation-row')) {
                return {'table': reservationsTable, 'status': 'active'};
            }
            else if ($reservationTr.hasClass('cancelled-reservation-row')) {
                return {'table': cancelledTable, 'status': 'cancelled'};
            }
            else if ($reservationTr.hasClass('noshow-reservation-row')) {
                return {'table': noshowTable, 'status': 'noshow'};
            }
            else if ($reservationTr.hasClass('waiting-reservation-row')) {
                return {'table': waitingTable, 'status': 'waiting'};
            }
        }

        return null;
    }

    function redrawTables() {

        if (!cancelledTable.data().count()) {
            $(".cancelled-section").hide();
            window.cancelledSection = false;
        }
        else if (!noshowTable.data().count()) {
            $(".noshow-section").hide();
            window.noshowSection = false;
        }
        else if (!waitingTable.data().count()) {
            $(".waiting-section").hide();
            window.waitingSection = false;
        }

        reservationsTable.columns.adjust().draw();
        cancelledTable.columns.adjust().draw();
        noshowTable.columns.adjust().draw();
        waitingTable.columns.adjust().draw();

    }

    /*** Records functions ***/
    window.lastDate = null;
    window.lastShift = null;
    window.lastCall = null;
    var recordsXhr = null;

    function getRecords() {

        //var callTime = new Date().getTime();

        var timeOfDay = getTimeOfDay();
        var filterDate = getFilterDate() || window.lastDate;

        var refresh = false;

        if (window.lastCall != null && window.lastDate == filterDate && window.lastShift == timeOfDay) {
            refresh = true;
        }

        if (!refresh) {
            reservationsTable.clear().draw();
            noshowTable.clear().draw();
            cancelledTable.clear().draw();
            waitingTable.clear().draw();

            $(".waiting-section").hide();
            window.waitingSection = false;

            $(".noshow-section").hide();
            window.noshowSection = false;

            $(".cancelled-section").hide();
            window.cancelledSection = false;

            $(".reservations-section").hide();
            $("#reservations-loader").show();
        }

        //abord previous get records ajax call if it's still in progress
        //before calling new one.
        if (recordsXhr && recordsXhr.readyState != 4) {
            recordsXhr.abort();
        }


        var url = window.location.protocol + "//" + window.location.hostname + "/reception/ajax/reservations/get-records/" + filterDate + "/" + timeOfDay;

        if (refresh) {
            url += "/" + window.lastCall;
        }
        if (api == 'preorders' || api == 'preorders_only') {
            url += "?" + api + "=true";
        }

        recordsXhr = $.ajax({
            method: "GET",
            url: url
        }).done(function (data) {

            $("#reservations-loader").hide();
            $(".reservations-section").show();

            var totalPersonsNum = parseInt(data['persons_num']);
            var red = data['red'];
            var orange = data['orange'];
            var onlineClosed = Boolean(data['online_closed']);
            var color = data['color'];
            var stoppedDayRecords = data['stopped_day_records'];

            var hourlyData = data['hourly_data'];

            var dayChanged = data['day_shift_changed'];
            var nightChanged = data['night_shift_changed'];

            handleStoppedDayLog(stoppedDayRecords);
            handleLightsData(totalPersonsNum, onlineClosed, red, orange, color);
            handleOffdays(timeOfDay, dayChanged, nightChanged);
//            handleHourlyData(hourlyData);

            // TODO
            handleHourlyAnalytics(hourlyData);

            var reservationsData = data['data'];

            for (var i = 0; i < reservationsData.length; i++) {

                //var insert = true;
                var reservationId = reservationsData[i]['id'];

                if (refresh) {
                    var tableData = getReservationTableById(reservationId);
                    if (tableData != null) {
                        var myTable = tableData['table'];
                        if (myTable != null) {
                            myTable.row("#reservation-tr-" + reservationId).remove().draw();
                        }
                    }
                }

                var tr = null;
                if (reservationsData[i]['status'] != null && reservationsData[i]['status']['name'] == 'cancelled') {
                    tr = insertRecord('cancelled', reservationsData[i]);
                }
                else if (reservationsData[i]['status'] != null && reservationsData[i]['status']['name'] == 'noshow') {
                    tr = insertRecord('noshow', reservationsData[i]);
                }
                else if (reservationsData[i]['status'] != null && reservationsData[i]['status']['name'] == 'waiting') {
                    tr = insertRecord('waiting', reservationsData[i]);
                }
                else {
                    //totalPersonsNum += parseInt(reservationsData[i].persons_num);
                    tr = insertRecord('active', reservationsData[i]);
                }

                if (refresh) {
                    tr.css('background-color', '#fff152');
                }
            }

            redrawTables();

            if (refresh) {

                setTimeout(function () {
                    setColors();
                }, 2000);
            }
            else {
                setColors();
            }

            window.lastCall = data['last'];
            window.lastDate = filterDate;
            window.lastShift = timeOfDay;

        }).fail(function (xhr, textStatus) {

            if (textStatus != "abort") {
                //display this error only when failure is not caused by
                //our intended abort
                alert('{{ trans('general.server_communication_error_msg') }}');
                location.reload();
            }
        });
    }
</script>

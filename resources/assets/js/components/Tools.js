export default function (options) {

    let $dateInput = $('.js-tools__controls-date--input');
    let $sidebarCalendar = $(".js-sidebar-calendar");
    let $todayButton = $(".js-tools__controls-date--today");
    let $timeOfDayButtons = $(".js-tools__controls-time--button");
    let $openTablePlanButton = $('.js-tools__controls-tables');
    let tablesDialog = $('.js-tableM__modal');
    let $statusPersonsNumber = $('.js-tools__statuses-radial-count--persons');
    let $statusPersonsMax = $('.js-tools__statuses-radial-count--all');
    let $radialProgress = $('.radial-progress');
    let $toolsStatusesButtons = $('.js-tools__statuses-online-button');
    let $onlineStatusesModal = $(".js-tools-lights__modal");
    let toolsAnalytics = $('.js-tools__analytics');


    function init(updateInterface) {

        initDatepicker(updateInterface);
        initTimeOfDayPicker(updateInterface);
        initTableView();
        updatePersonsRadialProgress();
        initOnlineStatus();
    }

    function updateTools(data) {
        // TODO change all namings
        let totalPersonsNum = parseInt(data['persons_num']);
        let orange = data['orange'];
        let onlineClosed = Boolean(data['online_closed']);
        let color = data['color'];
        let stoppedDayRecords = data['stopped_day_records'];

        let hourlyData = data['hourly_data'];

        let dayChanged = data['day_shift_changed'];
        let nightChanged = data['night_shift_changed'];


        let personsNum = data['persons_num'];
        let red = data['red'];

        updatePersonsRadialProgress(personsNum, red);
        updateOnlineStatus(data.color);
        updateAnalytics(hourlyData, red);

        // handleStoppedDayLog(stoppedDayRecords);
        // handleOffdays(timeOfDay, dayChanged, nightChanged);

    }

    function initTimeOfDayPicker(updateInterface) {
        $timeOfDayButtons.click(function () {
            let button = $(this);

            if (!button.hasClass('active')) {
                $timeOfDayButtons.removeClass('active');
                button.addClass('active');

                updateInterface();

                let selectedDate = $dateInput.datepicker('getDate');
                $sidebarCalendar.datepicker('setDate', selectedDate);
            }
        });
    }

    function initDatepicker(updateInterface) {

        options.language = 'de';
        console.log(options.language);

        $dateInput.datepicker({
            language: options.language,
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });

        $sidebarCalendar.datepicker({
            language: options.language,
            setDate: true,
            todayHighlight: true,
        });

        $dateInput.on('changeDate', function (e) {
            let thisDate = e.date;
            let selectedDate = $sidebarCalendar.datepicker('getDate');

            if (!thisDate || !selectedDate || thisDate.valueOf() !== selectedDate.valueOf()) {
                $sidebarCalendar.datepicker('setDate', e.date);
                updateInterface();
            }
        });

        $sidebarCalendar.on('changeDate', function (e) {
            let thisDate = e.date;
            let selectedDate = $dateInput.datepicker('getDate');

            if (!thisDate || !selectedDate || thisDate.valueOf() !== selectedDate.valueOf()) {
                $dateInput.datepicker('setDate', e.date);
                updateInterface();
            }
        });

        $todayButton.click(function () {

            let currentDate = new Date();

            $timeOfDayButtons.removeClass('active');

            let selectedDate = $dateInput.val();

            if (currentDate >= selectedDate) {
                $timeOfDayButtons.filter("[data-value='night']").addClass('active');
            } else {
                $timeOfDayButtons.filter("[data-value='day']").addClass('active');
            }

            $sidebarCalendar.datepicker('setDate', currentDate);
            $dateInput.datepicker('setDates', currentDate);
        })

    }

    function initTableView() {

        $openTablePlanButton.click(function () {
            let dateInput = $dateInput.val();
            let shift = $timeOfDayButtons.filter('.active').data('value');

            if (shift === 'all') {
                console.log('TODO select shift error');
            } else {
                let url = "/reception/ajax/table-plan-objects/" + dateInput + "/" + shift;

                $.ajax({
                    method: "GET",
                    url: url
                }).done(function (data) {

                        let sectionTabs = tablesDialog.find(".js-tableM__modal-header-item");
                        let tabContent = tablesDialog.find('.js-tableM__modal-content');
                        let sectionContents = tabContent.find('.section-content');


                        sectionTabs.removeClass('active');
                        sectionContents.removeClass("in active");

                        sectionTabs.first().addClass("active");
                        sectionContents.first().addClass("in active");

                        tablesDialog.find('.js-tableM__modal--title').text(data.table_plan.name);

                        tabContent.tablesM({
                            'tableViewFlag': true,
                            'tablePlanObjects': data.table_plan_objects,
                            'tablePlanId': data.table_plan.id
                        }).draw();

                        tablesDialog.modal(open);

                        // TODO show active table to reservationId
                        /*   if (reservationId) {

                               // make taken plans selected
                               let activeTableNumber = $('.table-table-input[data-reservation-id="' + reservationId + '"]').val();

                               tablesDialog.find('h3').text(data.table_plan.name);

                               tabContent.tablesM({
                                   'tableViewFlag': true,
                                   'tablePlanObjects': data.table_plan_objects,
                                   'tablesReserved': data.tables_reserved,
                                   'tablePlanId': data.table_plan.id,
                                   'tableActive': activeTableNumber,
                                   'tablePicked': function (tableId) {
                                       $(".table-table-input[data-reservation-id='" + reservationId + "']").val(tableId);
                                       tableBlur(reservationId, tableId);
                                       tablesDialog.modal('hide');
                                   }
                               }).draw();

                               let activeTable = $('.tableM-object.active').first();
                               if (activeTable && activeTable.length == 1) {
                                   let sectionContent = activeTable.closest('.section-content');
                                   sectionContent.addClass('in active');
                                   let sectionId = sectionContent.data('section-id');
                                   $('.section-tab[data-section-id="' + sectionId + '"]').addClass('active');
                               } else {
                                   sectionTabs.first().addClass("active");
                                   sectionContents.first().addClass("in active");
                               }
                           } */
                    }
                ).fail(function (xhr) {
                    console.log(options.noReservationsMsg);
                });
            }
        });
    }

    function initOnlineStatus() {
        $toolsStatusesButtons.click(function () {
            let timeOfDay = getTimeOfDay();

            // TODO if all day selected
            if (timeOfDay !== "all") {
                $onlineStatusesModal.modal(open);
            }
        });
    }

    function getTimeOfDay() {
        let timeOfDay = $timeOfDayButtons.filter('.active');
        if (!timeOfDay) {
            return "all";
        } else {
            return timeOfDay.data('value');
        }
    }

    function filterDate() {
        return $dateInput.val();
    }

    function updatePersonsRadialProgress(personsNumber = 0, maxPersonsNumber = 0) {
        $statusPersonsNumber.text(personsNumber);
        $statusPersonsMax.text('/' + maxPersonsNumber);

        for (let i = 0; i < $radialProgress.length; i++) {
            let totalProgress = $radialProgress[i].querySelector('circle').getAttribute('stroke-dasharray');
            $radialProgress[i].querySelector('.bar').style['stroke-dashoffset'] = totalProgress * personsNumber / maxPersonsNumber;
        }
    }

    function updateOnlineStatus(color) {
        $toolsStatusesButtons.removeClass('active');
        $toolsStatusesButtons.filter("[data-color='" + color + "']").addClass('active');
    }

    function updateAnalytics(data, maxPersons = 50) {

        let tableHtml = '<div class="tools__analytics-persons"><div>0</div><div>25%</div><div>50%</div><div>75%</div><div>100%</div></div>';

        for (let i in data) {
            let reservationsHeight = Math.round(data[i]['persons'] / 12) + '%';
            let personsHeight = Math.round((data[i]['persons'] / maxPersons) * 100) + '%';

            tableHtml += '<div class="tools__analytics-hours">' +
                '            <div class="tools__analytics-bars">' +
                '            <div class="tools__analytics-bars--reservations" style="height: ' + reservationsHeight + ';" title="' + data[i]['reservations'] + '"></div>' +
                '            <div class="tools__analytics-bars--persons" style="height:' + personsHeight + ';" title="' + data[i]['persons'] + '"></div>' +
                '            </div>' +
                '            <div class="tools__analytics-hour">\n' +
                '            ' + i + ' - ' + i + '.59' +
                '            </div></div>';
        }

        tableHtml += '</div>';

        toolsAnalytics.html(tableHtml);

        if ($(".js-tools__controls-time--button.active").data('value') === 'all') {
            toolsAnalytics.hide();
        } else {
            toolsAnalytics.show();
        }
    }

    /*
        function handleStoppedDayLog(records) {

            $("#stopped-day-log").html("");

            if (records && records.length) {
                for (var i = 0; i < records.length; i++) {

                    var id = records[i]['id'];

                    var recordHtml = '<div class="stopped-day-record" id="stopped-day-' + id + '">';
                    recordHtml += '<span class="stopped-day-record-time"><i>' + records[i]['created_at'] + '</i></span>';
                    recordHtml += ' - ';

                    if (records[i]['online_closed'] == 1) {
                        recordHtml += '{{ trans('reception.online_reservation_closed') }}';
                    }
                    else {
                        recordHtml += '{{ trans('reception.automatic_close_at') }} ' + records[i]['online_stop_num'];
                    }
                    recordHtml += ' - {{ trans('general.by') }} <i>' + records[i]['user'] + '</i>';
                    recordHtml += '</div>';

                    $("#stopped-day-log").append(recordHtml);
                }
            }
            else {
                $("#stopped-day-log").html("{{ trans('general.no_records_found') }}");
            }
        }
    */


    return {
        init: init,
        updateTools: updateTools,
        getTimeOfDay: getTimeOfDay,
        filterDate: filterDate,
    }
}

export default function newReservation(options) {

    let timeInput = $('.js-reception-new__input--time'),
        dateInput = $('.js-reception-new__input--date'),
        sidebarCalendar = $(".js-sidebar-calendar");



    // Watch for change in config, if there one - change config to custom
    $('.js-reception-new-config__checkbox, .js-reception-new-config__hours, .js-reception-new__menu').on('change', function () {
        $('.js-reception-new-config--type').val(1);
    });


    dateInput.datepicker({
        language: options.language,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true
    });

    timeInput.timepicker({
        timeFormat: 'H:mm',
        interval: 15,
        minTime: options.dayStart,
        maxTime: options.nightEnd,
        startTime: '10:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });

    timeInput.on('keydown', function (e) {
        e.preventDefault();
        return false;
    });

    sidebarCalendar.datepicker('setDate');

    dateInput.on('changeDate', function (e) {
        let thisDate = e.date;
        let selectedDate = sidebarCalendar.datepicker('getDate');

        if (!thisDate || !selectedDate || thisDate.valueOf() !== selectedDate.valueOf()) {
            sidebarCalendar.datepicker('setDate', e.date);
        }
    });

    sidebarCalendar.on('changeDate', function (e) {
        let thisDate = e.date;
        let selectedDate = dateInput.datepicker('getDate');

        if (!thisDate || !selectedDate || thisDate.valueOf() !== selectedDate.valueOf()) {
            dateInput.datepicker('setDate', e.date);
            dateInput.datepicker('setDate', e.date);
        }
    });
}
export default function (options) {

    let walkInButton = $('.js-reception-new__walk-in--button'),
        quickReservationButton = $('.js-reception-new__quick--button'),
        quickReservationModal = $('.js-reception-quick__modal');

    quickReservationButton.on('click', function () {

        quickReservationModal.find('form')[0].reset();

        let date = new Date();
        let hours = date.getHours();
        let minutes = date.getMinutes();

        if (minutes < 15) {
            minutes = 15;
        } else if (minutes < 30) {
            minutes = 30;
        } else if (minutes < 45) {
            minutes = 45;
        } else {
            minutes = 0;
            hours++;
        }

        $('.js-reception-quick__modal--time').timepicker({
            zindex: 1040,
            timeFormat: 'H:mm',
            interval: 15,
            minTime: options.dayStart,
            maxTime: options.nightEnd,
            defaultTime: hours + ':' + minutes,
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });

        $('.js-reception-quick__modal--date').datepicker({
            language: options.language,
            format: 'yyyy-mm-dd',
            todayHighlight: true,
        });

        quickReservationModal.modal('show');
    });


    walkInButton.on('click', function () {
        let modalWalkIn = $('.js-reception-walk-in__modal');
        modalWalkIn.find('form')[0].reset();
        modalWalkIn.modal('show');
    });

}
import Tools from '../../components/Tools';
import DataTable from './../../components/DataTable';

export default function (options) {

    let _tools = Tools(options);
    let _dataTable = DataTable(options);

    _tools.init(updateInterface);
    _dataTable.initReceptionTables();

    getRecords();

    window.refreshTimer = setInterval(getRecords, 15000);

    function updateInterface() {
        clearInterval(window.refreshTimer);
        getRecords();
        window.refreshTimer = setInterval(getRecords, 15000);
    }

    $(".owl-carousel").owlCarousel({
        items:3,
        autoWidth: true,
    });
    // console.log( $('.owl-carousel .owl-stage-outer'), $('.js-tableM__modal .modal-content'));
    // $('.owl-carousel .owl-stage-outer').css('max-width', $('.js-tableM__modal .modal-content').width() - 37 );


    $('.js-tableM__modal-header--button').on('click', function(){
        $('.owl-carousel').trigger('next.owl.carousel');
    });

    function getRecords() {
        let timeOfDay = _tools.getTimeOfDay();
        let filterDate = _tools.filterDate() || window.lastDate;

        let url = "/reception/ajax/reservations/get-records/" + filterDate + "/" + timeOfDay;

        $.ajax({
            method: "GET",
            url: url
        }).done(function (data) {

            _dataTable.updateReceptionTables(data.reservations);
            _tools.updateTools(data);

            window.lastDate = filterDate;

        }).fail(function (xhr, textStatus) {
            console.log('fail to get data in ajax');

            if (textStatus !== "abort") {
                //display this error only when failure is not caused by
                //our intended abort
                console.log('general.server_communication_error_msg');
            }
        });
    }

}






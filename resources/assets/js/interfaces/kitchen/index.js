import Tools from '../../components/Tools';
import DataTable from './../../components/DataTable';

export default function (options) {

    let _tools = Tools(options);
    let _dataTable = DataTable(options);

    _tools.init(updateInterface);
    _dataTable.initKitchenTables();

    getRecords();

    window.refreshTimer = setInterval(getRecords, 15000);

    function updateInterface() {
        clearInterval(window.refreshTimer);
        getRecords();
        window.refreshTimer = setInterval(getRecords, 15000);
    }

    function getRecords() {
        console.log('getRecords');
        let timeOfDay = _tools.getTimeOfDay();
        let filterDate = _tools.filterDate() || window.lastDate;

        let url = "/reception/ajax/reservations/get-records/" + filterDate + "/" + timeOfDay;

        $.ajax({
            method: "GET",
            url: url
        }).done(function (data) {

            _dataTable.updateKitchenTables(data.reservations);
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






export default function (options) {

    let $reservationsTable = $('.js-table-reservations'),
        $waitingTable = $('.js-table-waiting'),
        $canceledTable = $('.js-table-canceled'),
        $noshowTable = $('.js-table-noshow'),
        $searchInTablesInput = $(".js-tools__controls-filters--input-search");

    function initReceptionTables() {

        $reservationsTable.DataTable({
            paging: false,
            info: false,
            order: [[4, "asc"]],

            columns: [
                {data: 'persons_num'},
                {data: function ( row ) {
                    if ( row.client ) {
                        return row.client['first_name'] + ' ' + row.client['last_name'];
                    } else {
                        return "";
                    }
                }},
                {data: function( row ) {
                    if ( row.client ) {
                        return row.client['phone'] + ' ' + row.client['mobile'];
                    } else {
                        return "";
                    }
                }},
                {data: 'time'},
                {
                    data: 'table_id',
                    orderDataType: "dom-text-numeric", type: "numeric"
                },
                {data: function( row ) {
                    if ( row.status ) {
                        return row.status['name'];
                    } else {
                        return "";
                    }
                }},
                {data: function() {
                    return 'left';
                }},
                {data: 'created_by'},
                {data: function ( row ) {
                    if ( row.event_type ) {
                        return row.event_type['name'];
                    } else {
                        return "";
                    }
                }},
                {data: function( row ) {
                    return '<a href="/reception/reservations/' + row.id + '/edit"><svg class="companies-content__company--edit-icon js-companies-content__company--edit-icon">' +
                        '                                <use xlink:href="#icon-edit"></use>' +
                        '                            </svg></a>';

                }},
                {data: function( row ) {
                    return '<a href="javascript:"><svg class="reception-new__file-remove--icon">' +
                        '                                <use xlink:href="#icon-cross"></use>' +
                        '                            </svg></a>';

                }}
            ],
            oLanguage: {
                "sEmptyTable": options.noReservationsMsg
            }
        });

        $waitingTable.DataTable({
            paging: false,
            info: false,
            order: [[4, "asc"]],

            columns: [
                {data: 'persons_num'},
                // {data: 'client.last_name'},
                // {data: 'client.phone'},
                // {data: 'client.mobile'},
                {data: 'time'},
                {
                    data: 'table_id',
                    // orderDataType: "dom-text-numeric", "type": "numeric"
                },
                {data: 'status_id'},
                {data: 'left'},
                {data: 'event_type_id'},
                {data: 'left'},
                {data: 'left'}
            ],
            oLanguage: {
                "sEmptyTable": options.noReservationsMsg
            }
        });

        $canceledTable.DataTable({
            paging: false,
            info: false,
            order: [[5, "asc"]],

            columns: [
                {data: 'persons_num'},
                // {data: 'client.first_name'},
                // {data: 'client.phone'},
                // {data: 'client.mobile'},
                {data: 'time'},
                {
                    data: 'table_id',
                    // orderDataType: "dom-text-numeric", "type": "numeric"
                },
                {data: 'status_id'},
                {data: 'created_by'},
                {data: 'event_type_id'},
                {data: 'left'},
                {data: 'left'}
            ],
            oLanguage: {
                "sEmptyTable": options.noReservationsMsg
            }
        });

        $noshowTable.DataTable({
            paging: false,
            info: false,
            order: [[5, "asc"]],

            columns: [
                {data: 'persons_num'},
                // {data: 'client.first_name'},
                // {data: 'client.phone'},
                // {data: 'client.mobile'},
                {data: 'time'},
                {
                    data: 'table_id',
                    // orderDataType: "dom-text-numeric", "type": "numeric"
                },
                {data: 'status_id'},
                {data: 'left'},
                {data: 'event_type_id'},
                {data: 'left'},
                {data: 'left'}
            ],
            oLanguage: {
                "sEmptyTable": options.noReservationsMsg
            }
        });

        $searchInTablesInput.on('keyup', function () {
            $reservationsTable.DataTable().search(this.value).draw();
            $canceledTable.DataTable().search(this.value).draw();
            $noshowTable.DataTable().search(this.value).draw();
            $waitingTable.DataTable().search(this.value).draw();
        });

    }

    function initKitchenTables() {

        $reservationsTable.DataTable({
            paging: false,
            info: false,
            order: [[4, "asc"]],

            columns: [
                {data: 'persons_num'},
                {data: 'client.last_name'},
                {data: 'client.phone'},
                {data: 'client.mobile'},
                {data: 'time'},
                {
                    data: 'table_id',
                    orderDataType: "dom-text-numeric", type: "numeric"
                },
                {data: 'status_id'},
                {data: 'created_by'},
                {data: 'event_type_id'},
                {data: 'left'},
                {data: 'left'}
            ],
            oLanguage: {
                "sEmptyTable": options.noReservationsMsg
            }
        });

        $waitingTable.DataTable({
            paging: false,
            info: false,
            order: [[4, "asc"]],

            columns: [
                {data: 'persons_num'},
                {data: 'client.last_name'},
                {data: 'client.phone'},
                {data: 'client.mobile'},
                {data: 'time'},
                {
                    data: 'table_id',
                    // orderDataType: "dom-text-numeric", "type": "numeric"
                },
                {data: 'status_id'},
                {data: 'left'},
                {data: 'event_type_id'},
                {data: 'left'},
                {data: 'left'}
            ],
            oLanguage: {
                "sEmptyTable": options.noReservationsMsg
            }
        });

        $canceledTable.DataTable({
            paging: false,
            info: false,
            order: [[5, "asc"]],

            columns: [
                {data: 'persons_num'},
                {data: 'client.first_name'},
                {data: 'client.phone'},
                {data: 'client.mobile'},
                {data: 'time'},
                {
                    data: 'table_id',
                    // orderDataType: "dom-text-numeric", "type": "numeric"
                },
                {data: 'status_id'},
                {data: 'created_by'},
                {data: 'event_type_id'},
                {data: 'left'},
                {data: 'left'}
            ],
            oLanguage: {
                "sEmptyTable": options.noReservationsMsg
            }
        });

        $noshowTable.DataTable({
            paging: false,
            info: false,
            order: [[5, "asc"]],

            columns: [
                {data: 'persons_num'},
                {data: 'name'},
                {data: 'client.phone'},
                {data: 'client.mobile'},
                {data: 'time'},
                {
                    data: 'table_id',
                    // orderDataType: "dom-text-numeric", "type": "numeric"
                },
                {data: 'status_id'},
                {data: 'left'},
                {data: 'event_type_id'},
                {data: 'left'},
                {data: 'left'}
            ],
            oLanguage: {
                "sEmptyTable": options.noReservationsMsg
            }
        });

        $searchInTablesInput.on('keyup', function () {
            $reservationsTable.DataTable().search(this.value).draw();
            $canceledTable.DataTable().search(this.value).draw();
            $noshowTable.DataTable().search(this.value).draw();
            $waitingTable.DataTable().search(this.value).draw();
        });
    }

    function updateReceptionTables(data) {

        let reservations = data,
            index = 0,
            reservationsLength = reservations.length;

        $reservationsTable.DataTable().clear().draw();
        // $canceledTable.DataTable().clear().draw();
        // $noshowTable.DataTable().clear().draw();
        // $waitingTable.DataTable().clear().draw();

        console.log(reservations);

        for (index; index < reservationsLength; index++) {

            // 1 - active
            // 2 - cancelled
            // 3 - noshow
            // 4 - waiting
            if(reservations[index].status_id === 1) {
                $reservationsTable.DataTable().row.add(reservations[index]).draw();
            }

            if(reservations[index].status_id === 2) {
                $canceledTable.DataTable().row.add(reservations[index]).draw();
            }

            if(reservations[index].status_id === 3) {
                $noshowTable.DataTable().row.add(reservations[index]).draw();
            }

            if(reservations[index].status_id === 4) {
                $waitingTable.DataTable().row.add(reservations[index]).draw();
            }
        }
    }

    function updateKitchenTables(data) {

        let reservations = data,
            index = 0,
            reservationsLength = reservations.length;

        $reservationsTable.DataTable().clear().draw();
        $canceledTable.DataTable().clear().draw();
        $noshowTable.DataTable().clear().draw();
        $waitingTable.DataTable().clear().draw();

        for (index; index < reservationsLength; index++) {

            // 1 - active
            // 2 - cancelled
            // 3 - noshow
            // 4 - waiting
            if(reservations[index].status_id === 1) {
                $reservationsTable.DataTable().row.add(reservations[index]).draw();
            }

            if(reservations[index].status_id === 2) {
                $canceledTable.DataTable().row.add(reservations[index]).draw();
            }

            if(reservations[index].status_id === 3) {
                $noshowTable.DataTable().row.add(reservations[index]).draw();
            }

            if(reservations[index].status_id === 4) {
                $waitingTable.DataTable().row.add(reservations[index]).draw();
            }
        }
    }

    return {
        initReceptionTables: initReceptionTables,
        initKitchenTables: initKitchenTables,
        updateReceptionTables: updateReceptionTables,
        updateKitchenTables: updateKitchenTables,
    }
}

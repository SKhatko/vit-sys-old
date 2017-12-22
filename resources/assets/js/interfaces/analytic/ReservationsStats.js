export default function (options) {


    $('#date-range-picker').daterangepicker({
        "locale": {
            "format": 'YYYY-MM-DD'
        },
        "ranges": {
            "Today": [
                moment(),
                moment()
            ],
            "Yesterday": [
                moment().subtract(1, 'days'),
                moment().subtract(1, 'days')
            ],
            "Last 7 Days": [
                moment().subtract(7, 'days'),
                moment().subtract(1, 'days')
            ],
            "Last 30 Days": [
                moment().subtract(30, 'days'),
                moment().subtract(1, 'days')
            ],
            "This Month": [
                moment().startOf('month'),
                moment().endOf('month')
            ],
            "Last Month": [
                moment().subtract(1, 'month').startOf('month'),
                moment().subtract(1, 'month').endOf('month')
            ]
        },
        "startDate": "{{ $dateFrom }}",
        "endDate": "{{ $dateTo }}"
    }, function (start, end, label) {

    });

    $.ajax({
        method: "GET",
        url: '/analytics/ajax/reservations/source-data/' + options.dateFrom + '/' + options.dateTo,
    }).done(function (data) {

        $(".show-after-load").show();

        drawChart(data);
    });

    $('#date-range-picker').on('apply.daterangepicker', function () {
        $("#general-loader").show();
        $("#date-range-form").submit();
    });

    function formatDecimal(num) {
        return num.replace('.', options.decimalPoint);
    }

    function drawChart(data) {

        var reservationsData = [];
        var personsData = [];

        var totalReservations = 0;
        var totalPersons = 0;

        var otherReservations = 0;
        var otherPersons = 0;

        for (var key in data) {
            totalReservations += Number(data[key]['reservations']);
            totalPersons += Number(data[key]['persons']);
        }

        for (var key in data) {

            var reservations = Number(data[key]['reservations']);
            var persons = Number(data[key]['persons']);

            var reservationsPercent = reservations / totalReservations;
            var personsPercent = persons / totalPersons;

            if (reservationsPercent >= 0.01 && personsPercent >= 0.01) {
                reservationsData.push({
                    name: key,
                    y: reservations
                });

                personsData.push({
                    name: key,
                    y: persons
                });
            }
            else {
                otherReservations += reservations;
                otherPersons += persons;
            }

            reservationsPercent = formatDecimal((reservationsPercent * 100).toFixed(2));
            personsPercent = formatDecimal((personsPercent * 100).toFixed(2));

            var row = "<tr>";
            row += "<td>" + key + "</td><td>" + reservations + " (" + reservationsPercent + "%)</td><td>" + persons + " (" + personsPercent + "%)</td>";
            row += "</tr>";

            $("#data-table").append(row);
        }

        if (otherReservations > 0 || otherPersons > 0) {

            reservationsData.push({
                name: options.transOther,
                y
        :
            otherReservations
        })
            ;

            personsData.push({
                name: options.transOther,
                y
        :
            otherPersons
        })
            ;
        }

        $(".chart-container[data-report='reservations']").highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: options.transActiveReservations + '(' +totalReservations + ')'
            },
            credits
    :
        {
            enabled: false
        }
    ,
        tooltip: {
            pointFormat: '{series.name}: {point.y} <b>({point.percentage:.1f}%)</b>'
        }
    ,
        plotOptions: {
            pie: {
                allowPointSelect: true,
                    cursor
            :
                'pointer',
                    dataLabels
            :
                {
                    enabled: true,
                        format
                :
                    '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style
                :
                    {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        }
    ,
        series: [{
            name: 'Reservations',
            colorByPoint: true,
            data: reservationsData
        }]
    })
        ;

        $(".chart-container[data-report='persons']").highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: options.transActivePersons + '(' +totalPersons + ')'
            },
            credits
    :
        {
            enabled: false
        }
    ,
        tooltip: {
            pointFormat: '{series.name}: {point.y} <b>({point.percentage:.1f}%)</b>'
        }
    ,
        plotOptions: {
            pie: {
                allowPointSelect: true,
                    cursor
            :
                'pointer',
                    dataLabels
            :
                {
                    enabled: true,
                        format
                :
                    '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style
                :
                    {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        }
    ,
        series: [{
            name: 'Persons',
            colorByPoint: true,
            data: personsData
        }]
    })
        ;
    }

}
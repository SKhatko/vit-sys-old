export default function (options) {

    $(".stat-icon").hide();
    $(".stat-loader").show();

    $.ajax({
        method: "GET",
        url: '/analytics/ajax/reservations/statuses-data/' + options.months
    }).done(function (data) {

        $(".stat-loader").hide();
        $(".show-after-load").show();
        $(".stat-icon").show();

        drawChart(data);
        //$(".report-loader").hide();

        $(".chart-container").hide();
        $(".chart-container.active").show();
    });

    $(".chart-tab").click(function () {
        $(".chart-tab").removeClass('active');
        $(this).addClass('active');

        var reportType = $(this).attr('data-report');

        $(".chart-container").hide();
        $(".chart-container[data-report='" + reportType + "']").addClass('active').show();
    });

    $("select[name='months']").change(function () {
        $("#general-loader").show();
        $("#months-form").submit();
    });

    function formatDecimal(num) {
        return num.replace('.', options.decimalPoint);
    }

    function drawChart(data) {

        var total = [];
        var normal = [];
        var cancelled = [];
        var waiting = [];
        var noshow = [];

        var totalPersons = [];
        var normalPersons = [];
        var cancelledPersons = [];
        var waitingPersons = [];
        var noshowPersons = [];


        var totalTotal = 0;
        var totalNormal = 0;
        var totalCancelled = 0;
        var totalNoshow = 0;
        var totalWaiting = 0;

        var totalTotalPersons = 0;
        var totalNormalPersons = 0;
        var totalCancelledPersons = 0;
        var totalNoshowPersons = 0;
        var totalWaitingPersons = 0;

        var months = [];

        for (var key in data) {

            months.push(key);

            var thisTotal = Number(data[key]['total']);
            var thisTotalPersons = Number(data[key]['persons']);

            var thisNormalReservations = Number(data[key]['normal'])
            var thisNormalPersons = Number(data[key]['normal_persons']);

            var thisCancelledReservations = Number(data[key]['cancelled']);
            var thisCancelledPersons = Number(data[key]['cancelled_persons']);

            var thisWaitingReservations = Number(data[key]['waiting']);
            var thisWaitingPersons = Number(data[key]['waiting_persons']);

            var thisNoshowReservations = Number(data[key]['noshow']);
            var thisNoshowPersons = Number(data[key]['noshow_persons']);

            //TOTAL
            total.push(thisTotal);
            totalTotal += thisTotal;

            totalPersons.push(thisTotalPersons);
            totalTotalPersons += thisTotalPersons;

            //NORMAL
            normal.push(thisNormalReservations);
            totalNormal += thisNormalReservations;
            var normalReservationsPercent = thisTotal == 0 ? 0 : ((thisNormalReservations / thisTotal) * 100).toFixed(2);

            normalPersons.push(thisNormalPersons);
            totalNormalPersons += thisNormalPersons;
            var normalPersonsPercent = thisTotalPersons == 0 ? 0 : ((thisNormalPersons / thisTotalPersons) * 100).toFixed(2);

            //CANCELLED
            cancelled.push(thisCancelledReservations);
            totalCancelled += thisCancelledReservations;
            var cancelledReservationsPercent = thisTotal == 0 ? 0 : ((thisCancelledReservations / thisTotal) * 100).toFixed(2);

            cancelledPersons.push(Number(data[key]['cancelled_persons']));
            totalCancelledPersons += Number(data[key]['cancelled_persons']);
            var cancelledPersonsPercent = thisTotalPersons == 0 ? 0 : ((thisCancelledPersons / thisTotalPersons) * 100).toFixed(2);

            //WAITING
            waiting.push(thisWaitingReservations);
            totalWaiting += thisWaitingReservations;
            var waitingReservationsPercent = thisTotal == 0 ? 0 : ((thisWaitingReservations / thisTotal) * 100).toFixed(2);

            waitingPersons.push(thisWaitingPersons);
            totalWaitingPersons += thisWaitingPersons;
            var waitingPersonsPercent = thisTotalPersons == 0 ? 0 : ((thisWaitingPersons / thisTotalPersons) * 100).toFixed(2);

            //NOSHOW
            noshow.push(thisNoshowReservations);
            totalNoshow += thisNoshowReservations;
            var noshowReservationsPercent = thisTotal == 0 ? 0 : ((thisNoshowReservations / thisTotal) * 100).toFixed(2);

            noshowPersons.push(thisNoshowPersons);
            totalNoshowPersons += thisNoshowPersons;
            var noshowPersonsPercent = thisTotalPersons == 0 ? 0 : ((thisNoshowPersons / thisTotalPersons) * 100).toFixed(2);

            //Decimal point formatting for printing
            normalReservationsPercent = formatDecimal(normalReservationsPercent);
            normalPersonsPercent = formatDecimal(normalPersonsPercent);
            cancelledReservationsPercent = formatDecimal(cancelledReservationsPercent);
            cancelledPersonsPercent = formatDecimal(cancelledPersonsPercent);
            noshowReservationsPercent = formatDecimal(noshowReservationsPercent);
            noshowPersonsPercent = formatDecimal(noshowPersonsPercent);
            waitingReservationsPercent = formatDecimal(waitingReservationsPercent);
            waitingPersonsPercent = formatDecimal(waitingPersonsPercent);

            var tr = "<tr>";
            tr += "<td>" + key + "</td>";
            tr += "<td><span class='fa fa-list' title='" + options.transReservations + "'></span> " + thisTotal + " <br><span class='fa fa-user' title='{{ trans('analytics.persons') }}'></span> " + thisTotalPersons + "</td>";

            tr += "<td style='color:#00b200;'><span class='fa fa-list' title='" + options.transReservations + "'></span> " + thisNormalReservations + " (" + normalReservationsPercent + "%) <br><span class='fa fa-user' title='" + options.transPersons + "'></span> " + thisNormalPersons + " (" + normalPersonsPercent + "%)</td>";

            tr += "<td style='color:#ff0000;'><span class='fa fa-list' title='" + options.transReservations + "'></span> " + thisCancelledReservations + " (" + cancelledReservationsPercent + "%) <br><span class='fa fa-user' title='" + options.transPersons + "'></span> " + thisCancelledPersons + " (" + cancelledPersonsPercent + "%)</td>";

            tr += "<td style='color:#9d0000;'><span class='fa fa-list' title='" + options.transReservations + "'></span> " + thisNoshowReservations + " (" + noshowReservationsPercent + "%) <br><span class='fa fa-user' title='" + options.transPersons + "'></span> " + thisNoshowPersons + " (" + noshowPersonsPercent + "%)</td>";

            tr += "<td style='color:#fcb800;'><span class='fa fa-list' title='" + options.transReservations + "'></span> " + thisWaitingReservations + " (" + waitingReservationsPercent + "%) <br><span class='fa fa-user' title='" + options.transPersons + "'></span> " + thisWaitingPersons + " (" + waitingPersonsPercent + "%)</td>";

            tr += "</tr>";

            $("#data-table").append(tr);
        }

        var totalNormalReservationsPercent = totalTotal == 0 ? 0 : formatDecimal(((totalNormal / totalTotal) * 100).toFixed(2));
        var totalNormalPersonsPercent = totalTotalPersons == 0 ? 0 : formatDecimal(((totalNormalPersons / totalTotalPersons) * 100).toFixed(2));

        var totalCancelledReservationsPercent = totalTotal == 0 ? 0 : formatDecimal(((totalCancelled / totalTotal) * 100).toFixed(2));
        var totalCancelledPersonsPercent = totalTotalPersons == 0 ? 0 : formatDecimal(((totalCancelledPersons / totalTotalPersons) * 100).toFixed(2));

        var totalWaitingReservationsPercent = totalTotal == 0 ? 0 : formatDecimal(((totalWaiting / totalTotal) * 100).toFixed(2));
        var totalWaitingPersonsPercent = totalTotalPersons == 0 ? 0 : formatDecimal(((totalWaitingPersons / totalTotalPersons) * 100).toFixed(2));

        var totalNoshowReservationsPercent = totalTotal == 0 ? 0 : formatDecimal(((totalNoshow / totalTotal) * 100).toFixed(2));
        var totalNoshowPersonsPercent = totalTotalPersons == 0 ? 0 : formatDecimal(((totalNoshowPersons / totalTotalPersons) * 100).toFixed(2));


        $("#total-reservations-stat-holder").html(totalTotal);
        $("#normal-reservations-stat-holder").html(totalNormal);
        $("#cancelled-reservations-stat-holder").html(totalCancelled);
        $("#waiting-reservations-stat-holder").html(totalWaiting);
        $("#noshow-reservations-stat-holder").html(totalNoshow);

        $("#total-persons-stat-holder").html(totalTotalPersons);
        $("#normal-persons-stat-holder").html(totalNormalPersons);
        $("#cancelled-persons-stat-holder").html(totalCancelledPersons);
        $("#waiting-persons-stat-holder").html(totalWaitingPersons);
        $("#noshow-persons-stat-holder").html(totalNoshowPersons);

        $("#normal-reservations-percent-holder").html('(' + totalNormalReservationsPercent + '%)');
        $("#normal-persons-percent-holder").html('(' + totalNormalPersonsPercent + '%)');

        $("#cancelled-reservations-percent-holder").html('(' + totalCancelledReservationsPercent + '%)');
        $("#cancelled-persons-percent-holder").html('(' + totalCancelledPersonsPercent + '%)');

        $("#noshow-reservations-percent-holder").html('(' + totalNoshowReservationsPercent + '%)');
        $("#noshow-persons-percent-holder").html('(' + totalNoshowPersonsPercent + '%)');

        $("#waiting-reservations-percent-holder").html('(' + totalWaitingReservationsPercent + '%)');
        $("#waiting-persons-percent-holder").html('(' + totalWaitingPersonsPercent + '%)');

        highcharts.setOptions({
            colors: ['#00b200', '#ff0000', '#9d0000', '#fcb800', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
        });


        $(".chart-container[data-report='reservations']").highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: months,
                crosshair: true
            },
            yAxis: {
                title: {
                    text: ''
                }
            },
            tooltip: {
                shared: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: options.transNormalReservations,
                data
    :
        normal,
    },
        {
            name: opitons.transCanceledReservations,
                data
        :
            cancelled,
        }
    ,
        {
            name: options.transNoReservations,
                data
        :
            noshow,
        }
    ,
        {
            name: options.transWaitingReservations,
                data
        :
            waiting,
        }
    ]
    })
        ;


        $(".chart-container[data-report='persons']").highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: months,
                crosshair: true
            },
            yAxis: {
                title: {
                    text: ''
                }
            },
            tooltip: {
                shared: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: options.transNormal,
                data
    :
        normalPersons,
    },
        {
            name: options.transCanceled,
                data
        :
            cancelledPersons,
        }
    ,
        {
            name: options.transNoReservations,
                data
        :
            noshowPersons,
        }
    ,
        {
            name: options.transWaitingReservations,
                data
        :
            waitingPersons,
        }
    ]
    })
        ;

    }
}
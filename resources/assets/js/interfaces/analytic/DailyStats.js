export default function (options) {

    $(".stat-icon").hide();
    $(".stat-loader").show();

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
        "startDate": options.dateFrom,
        "endDate": options.dateTo
    }, function (start, end, label) {

    });

    $.ajax({
        method: "GET",
        url: '/analytics/ajax/reservations/daily-data/' + options.dateFrom + '/' + options.dateTo
    }).done(function (data) {

        $(".stat-loader").hide();
        $(".show-after-load").show();
        $(".stat-icon").show();

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


        var dates = [];
        var reservations = [];
        var persons = [];

        var totalReservationsNum = 0;
        var totalPersonsNum = 0;
        var totalNotShowed = 0;
        var totalNotShowedPersons = 0;
        var totalShowed = 0;
        var totalShowedPersons = 0;
        var totalWalkins = 0;
        var totalWalkinsPersons = 0;
        var totalOnline = 0;
        var totalOnlinePersons = 0;

        var weekendPlotBands = [];

        for (var key in data) {

            dates.push(key);

            var reservationsNum = Number(data[key]['reservations_num']);
            var personsNum = Number(data[key]['persons_num']);
            var walkins = Number(data[key]['walk_ins']);
            var walkinsPersons = Number(data[key]['walk_ins_persons']);
            var online = Number(data[key]['online']);
            var onlinePersons = Number(data[key]['online_persons']);
            var showed = Number(data[key]['showed']);
            var showedPersons = Number(data[key]['showed_persons']);
            var noShowed = reservationsNum - showed;
            var noShowedPersons = personsNum - showedPersons;

            var walkinsPercent = reservationsNum == 0 ? 0 : (walkins / reservationsNum) * 100;
            walkinsPercent = walkinsPercent.toFixed(2);

            var walkinsPersonsPercent = personsNum == 0 ? 0 : (walkinsPersons / personsNum) * 100;
            walkinsPersonsPercent = walkinsPersonsPercent.toFixed(2);


            var onlinePercent = reservationsNum == 0 ? 0 : (online / reservationsNum) * 100;
            onlinePercent = onlinePercent.toFixed(2);

            var onlinePersonsPercent = personsNum == 0 ? 0 : (onlinePersons / personsNum) * 100;
            onlinePersonsPercent = onlinePersonsPercent.toFixed(2);


            var noShowPercent = reservationsNum == 0 ? 0 : (noShowed / reservationsNum) * 100;
            noShowPercent = noShowPercent.toFixed(2);

            var noShowPersonsPercent = personsNum == 0 ? 0 : (noShowedPersons / personsNum) * 100;
            noShowPersonsPercent = noShowPersonsPercent.toFixed(2);


            var showPercent = reservationsNum == 0 ? 0 : (showed / reservationsNum) * 100;
            showPercent = showPercent.toFixed(2);

            var showPersonsPercent = personsNum == 0 ? 0 : (showedPersons / personsNum) * 100;
            showPersonsPercent = showPersonsPercent.toFixed(2);


            var personsPerReservation = reservationsNum == 0 ? 0 : personsNum / reservationsNum;
            personsPerReservation = personsPerReservation.toFixed(2);


            reservations.push(reservationsNum);
            persons.push(personsNum);


            //totals
            totalReservationsNum += reservationsNum;
            totalPersonsNum += personsNum;
            totalShowed += showed;
            totalShowedPersons += showedPersons;
            totalNotShowed += noShowed;
            totalNotShowedPersons += noShowedPersons;
            totalWalkins += walkins;
            totalWalkinsPersons += walkinsPersons;
            totalOnline += online;
            totalOnlinePersons += onlinePersons;


            //format decimals for printing
            showPercent = formatDecimal(showPercent);
            showPersonsPercent = formatDecimal(showPersonsPercent);
            noShowPercent = formatDecimal(noShowPercent);
            noShowPersonsPercent = formatDecimal(noShowPersonsPercent);
            walkinsPercent = formatDecimal(walkinsPercent);
            walkinsPersonsPercent = formatDecimal(walkinsPersonsPercent);
            onlinePercent = formatDecimal(onlinePercent);
            onlinePersonsPercent = formatDecimal(onlinePersonsPercent);

            personsPerReservation = formatDecimal(personsPerReservation);

            //mark weekends
            var dateParts = key.split("-");
            var thisDate = new Date();
            thisDate.setFullYear(dateParts[0]);
            thisDate.setMonth(dateParts[1] - 1);
            thisDate.setDate(dateParts[2]);

            if (thisDate.getDay() == 6 || thisDate.getDay() == 0) {
                var index = dates.length - 1.5;
                weekendPlotBands.push({
                    from: index,
                    to: index + 1,
                    color: 'rgba(68, 170, 213, .2)'
                });
                var row = '<tr class="weekend-row">';
            }
            else {
                var row = '<tr>';
            }


            row += '<td>' + key + '</td><td><span class="fa fa-list" title="' + options.transReservations + '"></span> <span class="reservations-stat">' + reservationsNum + '</span><br><span class="fa fa-user" title="' + options.transPersons + '"></span> <span class="persons-stat">' + personsNum + '</span></td>';

            row += '<td>' + personsPerReservation + '</td>';

            row += '<td style="color:green;"><span class="fa fa-list" title="' + options.transReservations + '"></span> <span class="reservations-stat">' + showed + ' (' + showPercent + '%)</span><br><span class="fa fa-user" title="' + options.transPersons + '"></span> <span class="persons-stat">' + showedPersons + ' (' + showPersonsPercent + '%)</span></td>';

            row += '<td style="color:red;"><span class="fa fa-list" title="' + options.transReservations + '"></span> <span class="reservations-stat">' + noShowed + ' (' + noShowPercent + '%)</span><br><span class="fa fa-user" title="' + options.transPersons + '"></span> <span class="persons-stat">' + noShowedPersons + ' (' + noShowPersonsPercent + '%)</span></td>';

            row += '<td><span class="fa fa-list" title="' + options.transReservations + '"></span> <span class="reservations-stat">' + walkins + ' (' + walkinsPercent + '%)</span><br><span class="fa fa-user" title="' + options.transPersons + '"></span> <span class="persons-stat">' + walkinsPersons + ' (' + walkinsPersonsPercent + '%)</span></td>';

            row += '<td><span class="fa fa-list" title="' + options.transReservations + '"></span> <span class="reservations-stat">' + online + ' (' + onlinePercent + '%)</span><br><span class="fa fa-user" title="' + options.transPersons + '"></span> <span class="persons-stat">' + onlinePersons + ' (' + onlinePersonsPercent + '%)</span></td>';

            row += '</tr>';

            $("#data-table").append(row);
        }

        //var personsPerReservationStat = totalReservationsNum == 0 ? 0 : (totalPersonsNum/totalReservationsNum).toFixed(2);
        var notMarkedShowPercent = totalReservationsNum == 0 ? 0 : formatDecimal(((totalNotShowed / totalReservationsNum) * 100).toFixed(2));

        var markedShowPercent = totalReservationsNum == 0 ? 0 : formatDecimal(((totalShowed / totalReservationsNum) * 100).toFixed(2));
        var walkInsPercent = totalReservationsNum == 0 ? 0 : formatDecimal(((totalWalkins / totalReservationsNum) * 100).toFixed(2));
        var onlinePercent = totalReservationsNum == 0 ? 0 : formatDecimal(((totalOnline / totalReservationsNum) * 100).toFixed(2));

        var notMarkedShowPersonsPercent = totalPersonsNum == 0 ? 0 : formatDecimal(((totalNotShowedPersons / totalPersonsNum) * 100).toFixed(2));
        var markedShowPersonsPercent = totalPersonsNum == 0 ? 0 : formatDecimal(((totalShowedPersons / totalPersonsNum) * 100).toFixed(2));
        var walkInsPersonsPercent = totalPersonsNum == 0 ? 0 : formatDecimal(((totalWalkinsPersons / totalPersonsNum) * 100).toFixed(2));
        var onlinePersonsPercent = totalPersonsNum == 0 ? 0 : formatDecimal(((totalOnlinePersons / totalPersonsNum) * 100).toFixed(2));

        $("#total-reservations-stat-holder").html(totalReservationsNum);
        $("#total-persons-stat-holder").html(totalPersonsNum);
        //$("#persons-per-reservation-stat-holder").html(personsPerReservationStat);

        $("#marked-showed-stat-holder").html(totalShowed);
        $("#marked-showed-percent-holder").html('(' + markedShowPercent + '%)');
        $("#marked-showed-persons-stat-holder").html(totalShowedPersons);
        $("#marked-showed-persons-percent-holder").html('(' + markedShowPersonsPercent + '%)');

        $("#not-marked-showed-stat-holder").html(totalNotShowed);
        $("#not-marked-showed-percent-holder").html('(' + notMarkedShowPercent + '%)');
        $("#not-marked-showed-persons-stat-holder").html(totalNotShowedPersons);
        $("#not-marked-showed-persons-percent-holder").html('(' + notMarkedShowPersonsPercent + '%)');

        $("#walk-ins-stat-holder").html(totalWalkins);
        $("#walk-ins-percent-holder").html('(' + walkInsPercent + '%)');
        $("#walk-ins-persons-stat-holder").html(totalWalkinsPersons);
        $("#walk-ins-persons-percent-holder").html('(' + walkInsPersonsPercent + '%)');

        $("#online-stat-holder").html(totalOnline);
        $("#online-percent-holder").html('(' + onlinePercent + '%)');
        $("#online-persons-stat-holder").html(totalOnlinePersons);
        $("#online-persons-percent-holder").html('(' + onlinePersonsPercent + '%)');

        Highcharts.setOptions({
            colors: ['#b71414', '#3b3a2d', '#5a5a5a', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
        });

        $('#chart-container').highcharts({
            chart: {
                zoomType: 'xy',
                type: 'area'
            },
            credits: {
                enabled: false
            },
            title: {
                text: ''
            },
            xAxis: [{
                categories: dates,
                crosshair: true,
                plotBands: weekendPlotBands
            }],
            yAxis: [{ // Primary yAxis
                gridLineWidth: 0,
                title: {
                    text: options.transReservations,
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                labels: {
                    // format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                }

            }, { // Tertiary yAxis
                gridLineWidth: 0,
                title: {
                    text: options.transPersons,
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                labels: {
                    //format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 65,
                verticalAlign: 'top',
                y: 30,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
            },
            plotOptions: {
                area: {
                    marker: {
                        enabled: false,
                        symbol: 'circle',
                        radius: 2,
                        states: {
                            hover: {
                                enabled: true
                            }
                        }
                    }
                },
                series: {
                    events: {
                        legendItemClick: function () {
                            if (this.index == 0) {
                                if (this.visible) {
                                    //console.log('hide reservations data');
                                    $(".reservations-stat").css('opacity', '0.2');
                                    $(".fa-list").css('opacity', '0.2');
                                }
                                else {
                                    //console.log('show reservations data');
                                    $(".reservations-stat").css('opacity', '1');
                                    $(".fa-list").css('opacity', '1');
                                }
                            }
                            else if (this.index == 1) {
                                if (this.visible) {
                                    //console.log('hide persons data');
                                    $(".persons-stat").css('opacity', '0.2');
                                    $(".fa-user").css('opacity', '0.2');
                                }
                                else {
                                    //console.log('show persons data');
                                    $(".persons-stat").css('opacity', '1');
                                    $(".fa-user").css('opacity', '1');
                                }
                            }
                        }
                    }
                }
            },
            series: [{
                name: options.transReservationsNum,
                type: 'spline',
                yAxis: 0,
                data: reservations,
                // tooltip: {
                //    valueSuffix: '$'
                //}

            }, {
                name: options.transPersonsNum,
                type: 'spline',
                yAxis: 1,
                data: persons,
                //marker: {
                //    enabled: false
                //},
                //dashStyle: 'shortdot',
                //tooltip: {
                //    valueSuffix: '%'
                //}

            }]
        });
    }
}
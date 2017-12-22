<script>
    $(function () {

        // TODO remove all

        var shiftSwap = '{!! \App\Config::$day_end !!}';
        var noTablePlanErrorMsg = '{!! trans('reception.no_table_plan_found_msg') !!}';
        var shiftHours = shiftSwap.split(':')[0];
        var shiftMinutes = shiftSwap.split(':')[1];
        var tableInput = $('#form-table-id');
        var dateInput = $('#date');
        var timeHoursInput = $('#time_h');
        var tableMbutton = $(".tableM-btn");
        var tooltips = $('[data-toggle="tooltip"]');
        tooltips.tooltip({placement: 'auto', trigger: 'manual'});


        tableMbutton.click(function (e) {

            if (!dateInput.val()) {
                e.preventDefault();
                dateInput.tooltip('show');
            } else if (!timeHoursInput.val()) {
                e.preventDefault();
                timeHoursInput.tooltip('show');
            } else {
                var timeMinutesInput = $('#time_m option:selected');
                var timeHours = +timeHoursInput.val();
                var timeMinutes = +timeMinutesInput.val();
                var shift = '';

                if (timeHours >= +shiftHours && timeMinutes >= +shiftMinutes) {
                    shift = 'night';
                } else {
                    shift = 'day';
                }

                var url = window.location.protocol + "//" + window.location.hostname + "/reception/ajax/table-plan-objects/" + dateInput + "/" + shift;
                $.ajax({
                    method: "GET",
                    url: url
                }).done(function (data) {

                    var tablesDialog = $('#tablesM-modal');

                    var sectionTabs = $(".section-tab");
                    var tabContent = $('.tab-content');
                    var sectionContents = $('.section-content');

                    tablesDialog.modal('show');
                    tabContent.fadeIn('slow');

                    sectionTabs.removeClass('active');
                    sectionContents.removeClass("in active");

                    sectionTabs.first().addClass("active");
                    sectionContents.first().addClass("in active");

                    tablesDialog.find('h3').text(data.table_plan.name);

                    tabContent.tablesM({
                        'tableViewFlag': true,
                        'tablePlanObjects': data.table_plan_objects,
                        'tablePlanId': data.table_plan_id,
                        'tablePicked': function (tableNumber) {
                            tableInput.val(tableNumber);
                            $("#tablesM-modal").modal('hide');
                        }
                    }).draw();
                }).fail(function (xhr) {
                    alert(noTablePlanErrorMsg);
                });
            }
        });

        $("#delete-offer-link").click(function () {
            $("#offer-link").remove();
            $("input[name='delete_offer']").val("1");
        });

    });
</script>
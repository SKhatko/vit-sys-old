export default function (options) {

    let $document = $(document),
        $adminHours = $document.find('.js-admin-hours'),
        $basicTimeMorning = $adminHours.find('.js-admin-hours__basic-shift--morning'),
        $basicTimeDay = $adminHours.find('.js-admin-hours__basic-shift--day'),
        $basicTimeNight = $adminHours.find('.js-admin-hours__basic-shift--night'),
        $dayNameSelect = $adminHours.find('.js-admin-hours__daily-days-day--name'),
        $dayNameSwitch = $adminHours.find('.js-admin-hours__daily-days-day--switch'),
        $dayItems = $adminHours.find('.js-admin-hours__daily-days-day'),
        $dayTimes = $adminHours.find('.js-admin-hours__daily-times-items'),
        $checkboxAllTimes = $adminHours.find('.js-admin-hours__daily-times-all--checkbox'),

        $customTimeShiftSelect = $adminHours.find('.js-admin-hours__custom-top-shift--select'),
        $dropdownHours = $adminHours.find('.js-admin-hours__custom-top-time-dropdown'),
        $dropdownMenuShifts = $adminHours.find('.js-admin-hours__custom-top-time-dropdown-shift'),
        $dropdownButton = $adminHours.find('.js-admin-hours__custom-top-time-dropdown--button'),
        $selectAllCustomHoursCheckbox = $adminHours.find('.js-admin-hours__custom-top-time-dropdown-menu-item--all-checkbox'),

        $editCustomDayButton = $adminHours.find('.js-admin-hours__custom-table-content--edit'),
        $deleteCustomDayBUtton = $adminHours.find('.js-admin-hours__custom-table-content--delete'),

        $editCustomDayModal = $('.js-admin-hours__modal-edit'),
        $editCustomDayModalDate = $editCustomDayModal.find('.js-admin-hours__modal-edit-top--date'),
        $editCustomDayModalShift = $editCustomDayModal.find('.js-admin-hours__modal-edit-top--shift'),
        $editCustomDayModalSwitch = $editCustomDayModal.find('.js-admin-hours__modal-edit-status--switch'),
        $editCustomDayModalHours = $editCustomDayModal.find('.js-admin-hours__modal-edit-time-dropdown'),
        $editCustomDayModalDropdown = $editCustomDayModal.find('.js-admin-hours__modal-edit-time-dropdown--button'),
        $editCustomDayModalDropdownShifts = $editCustomDayModal.find('.js-admin-hours__modal-edit-time-dropdown-shift'),
        $editCustomDayModalReason = $editCustomDayModal.find('.js-admin-hours__modal-edit--reason'),
        $editCustomDayModalCheckboxAll = $editCustomDayModal.find('.js-admin-hours__modal-edit-time-dropdown-menu-item--all-checkbox'),
        $editCustomDayModalCheckboxes = $editCustomDayModal.find('.js-admin-hours__modal-edit-time-dropdown-menu-item--checkbox'),

        $deleteCustomDayModal = $('.js-admin-hours__modal-delete'),

        offdays = JSON.parse(options.offdays);

    initListeners();

    function initListeners() {

        console.log(offdays);

        $dayNameSelect.click(activateDay);

        $dayNameSwitch.change(activateDay);

        $checkboxAllTimes.change(selectAllTimes);

        $selectAllCustomHoursCheckbox.click(selectAllCustomHours);

        $document.click(stopDocumentPropagation);

        $dropdownButton.click(activateShiftHoursDropdown);

        $editCustomDayButton.click(editCustomDay);

        $deleteCustomDayBUtton.click(deleteCustomDay);

        $editCustomDayModalDropdown.click(activateModalDropdown);

        $editCustomDayModalCheckboxAll.click(selectAllCustomHours);

    }

    function activateModalDropdown(e) {
        e.stopPropagation();

        $editCustomDayModalHours.toggleClass('active');
    }

    function selectAllCustomHours() {

        let $this = $(this),
            thisChecked = $this.prop('checked');

        $this.closest('.js-admin-hours__custom-top-time-dropdown-shift')
            .find('.js-admin-hours__custom-top-time-dropdown-menu-item--checkbox')
            .prop('checked', thisChecked);

        $this.closest('.js-admin-hours__modal-edit-time-dropdown-shift')
            .find('.js-admin-hours__modal-edit-time-dropdown-menu-item--checkbox')
            .prop('checked', thisChecked)
    }

    function deleteCustomDay() {
        let form = $deleteCustomDayModal.find('form');
        let offdayId = $(this).closest('.js-admin-hours__custom-table-content-item').data('offday-id');

        $(form).attr('action', '/admin/offdays/' + offdayId);

        $deleteCustomDayModal.modal(open);
    }

    function editCustomDay() {

        let $form = $editCustomDayModal.find('form'),
            $offday = $(this).closest('.js-admin-hours__custom-table-content-item'),
            offdayId = $offday.data('offday-id'),
            offdayShift = $offday.find('.js-admin-hours__custom-table-content--time').text(),
            offdayDate = $offday.find('.js-admin-hours__custom-table-content--date').text(),
            offday = {};


        for(let i in offdays) {
            if(offdays[i].id === offdayId) {
                offday = offdays[i];
            }
        }

        $form.attr('action', '/admin/offdays/' + offdayId);

        $editCustomDayModalDate.text(offdayDate);
        $editCustomDayModalShift.text(offdayShift);
        $editCustomDayModalReason.text(offday['reason_for_change']);
        $editCustomDayModalSwitch.prop('checked', offday.enabled);
        $editCustomDayModalDropdownShifts.removeClass('active');
        $editCustomDayModalDropdownShifts.filter('[data-custom-time="' + offday.shift + '"]').addClass('active');

        $editCustomDayModalCheckboxes.each(function() {
            let $this = $(this);

            if($.inArray($this.val(), offday.times)) {
                $this.attr('checked', true);
            } else {
                $this.attr('checked', false);
                console.log($this.id);
            }

        });

        $editCustomDayModal.modal(open);
    }

    function activateShiftHoursDropdown(e) {
        e.stopPropagation();

        let customTime = $customTimeShiftSelect.val();

        $dropdownMenuShifts.removeClass('active');
        $dropdownMenuShifts.filter('[data-custom-time="' + customTime + '"]').addClass('active');
        $dropdownHours.toggleClass('active');
    }

    function stopDocumentPropagation(e) {
        e.stopPropagation();

        let $target = $(e.target);

        if (!($target.closest('.js-admin-hours__custom-top-time-dropdown').length)) {
            $dropdownHours.removeClass('active');
        }

        if (!($target.closest('.admin-hours__daily').length)) {
            $dayItems.removeClass('active');
            $dayTimes.removeClass('active');
        }

        if (!($target.closest('.js-admin-hours__modal-edit-time-dropdown').length)) {
            $editCustomDayModalHours.removeClass('active');
        }
    }

    function activateDay() {
        let $this = $(this),
            $thisDayItem = $this.closest('.js-admin-hours__daily-days-day'),
            $thisDay = $thisDayItem.data('day');

        if (!$thisDayItem.hasClass('active')) {
            $dayItems.removeClass('active');
            $thisDayItem.addClass('active');
            $dayTimes.removeClass('active');
            $dayTimes.filter('[data-day="' + $thisDay + '"]').addClass('active');
        }
    }

    function selectAllTimes() {
        let $this = $(this),
            $thisDay = $this.closest('.js-admin-hours__daily-times-items'),
            $thisChecked = $this.prop('checked');

        $thisDay.find('.js-admin-hours__daily-times-item--checkbox').each(function () {
            $(this).prop('checked', $thisChecked);
        })
    }

    let timePickerOptions = {
        dynamic: false,
        dropdown: true,
        scrollbar: true,
        timeFormat: 'HH:mm:ss',
        interval: options.interval,
    };

    $basicTimeMorning.timepicker($.extend({
        maxTime: options.dayEnd,
        defaultTime: options.dayStart,
    }, timePickerOptions));

    $basicTimeDay.timepicker($.extend({
        minTime: options.dayStart,
        maxTime: options.nightEnd,
        defaultTime: options.dayEnd,
    }, timePickerOptions));

    $basicTimeNight.timepicker($.extend({
        minTime: options.dayEnd,
        defaultTime: options.nightEnd,
    }, timePickerOptions));

    $('.js-admin-hours__custom-top--date').datepicker({
        language: options.language,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true
    });


}
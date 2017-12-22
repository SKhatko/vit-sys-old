export default function (options) {

    let deleteModal = $('.restaurant-schedule__modal-delete');
    let editModal = $('.js-restaurant-schedule__modal-edit');
    let rewriteModal = $('.js-restaurant-schedule__modal-rewrite');
    let dayPlanSelect = $('.js-restaurant-schedule__modal-edit--day');
    let nightPlanSelect = $('.js-restaurant-schedule__modal-edit--night');
    let $submitRewriteButton = $('.js-restaurant-schedule__modal-rewrite--submit');
    let $submitScheduleButton = $('.js-restaurant-schedule__custom--submit');

    $('.js-restaurant-schedule__records-item--delete').on('click', function () {
        let form = deleteModal.find('form');
        let recordItem = $(this).closest('.js-restaurant-schedule__records-item');
        let recordId = recordItem.data('record-id');

        $(form).attr('action', '/restaurant/table-plan-records/' + recordId + '/remove');

        deleteModal.modal(open);
    });

    $('.js-restaurant-schedule__daily-item--edit').on('click', function () {
        let dayItem = $(this).closest('.js-restaurant-schedule__daily-item');
        let dayNameText = dayItem.find('.js-restaurant-schedule__daily-item--name').text();
        let dayNameAttr = dayItem.data('day');
        let dayPlanId = dayItem.data('day-id');
        let nightPlanId = dayItem.data('night-id');

        $('.js-restaurant-schedule__modal-edit--title').text($.trim(dayNameText));
        dayPlanSelect.attr('name', dayNameAttr + '_day').val(dayPlanId);
        nightPlanSelect.attr('name', dayNameAttr + '_night').val(nightPlanId);

        editModal.modal(open);
    });
    $submitScheduleButton.on('click', function(event){
        $('.js-restaurant-schedule__records-item').each( function( i, e ){
            if($(e).children('.restaurant-schedule__records-item--date').text().trim() === $('.js-restaurant-schedule__custom-date').val()){
                if( $(e).children('.restaurant-schedule__records-item--time').data('time-of-day').trim().toLowerCase() === $('.restaurant-schedule__custom-shift select').val() ){
                    event.preventDefault();
                    rewriteModal.modal(open);
                }
            }
        });
    });
    $submitRewriteButton.on('click', function(e){
        $submitScheduleButton.closest('form').submit();
    });
    $('.js-restaurant-schedule__custom-date').datepicker({
        language: options.language,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        minDate: new Date(),
        startDate: new Date()
    });

}
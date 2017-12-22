export default function () {

    let editModal = $('.js-restaurant-plans__modal-edit');
    let deleteModal = $('.restaurant-plans__modal-delete');
    let sortablePlans = $('.js-restaurant-plans__list-content');

    $('.js-restaurant-plans__list-item--edit').on('click', function () {
        let form = editModal.find('form');
        let tablePlan = $(this).closest('.js-restaurant-plans__list-item');
        let planId = tablePlan.data('plan');
        let planName = tablePlan.find('.js-restaurant-plans__list-item--name').text();

        form[0].reset();

        $(form).attr('action', '/restaurant/table-plans/' + planId);
        $('.js-restaurant-plans__modal--name').val($.trim(planName));

        editModal.modal(open);
    });

    $('.js-restaurant-plans__list-item--delete').on('click', function () {
        let form = deleteModal.find('form');
        let tablePlan = $(this).closest('.js-restaurant-plans__list-item');
        let planId = tablePlan.data('plan');

        form[0].reset();

        $(form).attr('action', '/restaurant/table-plans/' + planId);

        deleteModal.modal(open);
    });

    sortablePlans.sortable({
        handle: ".js-restaurant-plans__list-item-icon",
        containment: ".restaurant-plans__list",
        update() {
            let data = $(this).sortable('toArray');

            axios.post('/restaurant/ajax/table-plans/order', {data})
        }
    });
}

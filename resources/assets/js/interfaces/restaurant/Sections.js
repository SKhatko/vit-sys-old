export default function () {

    let editModal = $('.js-restaurant-sections__modal-edit');
    let deleteModal = $('.restaurant-sections__modal-delete');
    let sortableSections = $(".js-restaurant-sections__list-content");

    $('.js-restaurant-sections__list-item--edit').on('click', function () {
        let form = editModal.find('form');
        let section = $(this).closest('.js-restaurant-sections__list-item');
        let sectionId = section.data('section');
        let sectionName = section.find('.js-restaurant-sections__list-item--name').text();
        let sectionDescription = section.find('.js-restaurant-sections__list-item--desc').text();

        form[0].reset();

        $(form).attr('action', '/restaurant/sections/' + sectionId);
        $('.js-restaurant-sections__modal--name').val($.trim(sectionName));
        $('.js-restaurant-sections__modal--desc').text($.trim(sectionDescription));

        editModal.modal(open);
    });

    $('.js-restaurant-sections__list-item--delete').on('click', function () {
        let form = deleteModal.find('form');
        let section = $(this).closest('.js-restaurant-sections__list-item');
        let sectionId = section.data('section');

        form[0].reset();

        $(form).attr('action', '/restaurant/sections/' + sectionId);

        deleteModal.modal(open);
    });

    sortableSections.sortable({
        handle: ".js-restaurant-sections__list-item-icon",
        containment: ".restaurant-sections__list",
        update() {
            let data = $(this).sortable('toArray');

            axios.post('/restaurant/ajax/sections/order', {data})
        }
    });

}


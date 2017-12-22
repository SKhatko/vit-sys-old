export default function () {

    let menusContent = $('.js-restaurant-menus__content');
    let removeMenuButton = $(".js-restaurant-menus__group--delete");
    let deleteMenuModal = $('.js-restaurant-menus__modal-delete');

    menusContent.sortable({
        items: '> .js-restaurant-menus__group',
        handle: ".js-restaurant-menus__group--icon",
        update() {
            let data = $(this).sortable('serialize');

            $.ajax({
                data: data,
                type: "GET",
                url: "/restaurant/ajax/custom-menus/order"
            })
        }
    });

    removeMenuButton.on('click', function () {
        let form = deleteMenuModal.find('form');
        let item = $(this).closest('.js-restaurant-menus__group');
        let itemName = item.find('.js-restaurant-menus__group--name').attr('title');
        let itemId = item.data('menu');
        console.log(itemName);
        form.find('.js-restaurant-menus__modal-delete--content-name').html(itemName);

        $(form).attr('action', '/restaurant/custom-menus/' + itemId);

        deleteMenuModal.modal(open);
    });

}
export default function () {

    let groupsContent = $('.js-restaurant-groups__content');
    let removeGroupButton = $(".js-restaurant-groups__group--delete");
    let deleteGroupModal = $('.js-restaurant-groups__modal-delete');

    groupsContent.sortable({
        items: '> .js-restaurant-groups__group',
        handle: ".js-restaurant-groups__group--icon",
        update() {
            let data = $(this).sortable('serialize');

            $.ajax({
                data: data,
                type: "GET",
                url: "/restaurant/ajax/menu/groups/order"
            })
        }
    });

    removeGroupButton.on('click', function () {
        let form = deleteGroupModal.find('form');
        let item = $(this).closest('.js-restaurant-groups__group');
        let itemName = item.find('.js-restaurant-groups__group--name').attr('title');
        let itemId = item.data('group');
        console.log(itemName);

        form.find('.js-restaurant-groups__modal-delete--content-name').html(itemName);

        $(form).attr('action', '/restaurant/menu/groups/' + itemId);

        deleteGroupModal.modal(open);
    });

}
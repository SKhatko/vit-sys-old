export default function () {

    let viewListButton = $('.js-restaurant-items-header__view-list');
    let viewBlocksButton = $('.js-restaurant-items-header__view-block');
    let itemsContent = $('.js-restaurant-items-content');
    let selectCategory = $(".js-restaurant-items-header__select-category");
    let itemCheckboxes = $('.js-restaurant-items-content__item-select--checkbox');
    let selectAllCheckbox = $(".js-restaurant-items-header__select-all--checkbox");
    let headerToolsPanel = $('.js-restaurant-items-header-move');
    let removeItem = $('.js-restaurant-items-content__item--delete-icon');
    let removeItemsButton = $(".js-restaurant-items-header__remove-item");
    let deleteItemModal = $('.js-restaurant-items__modal-delete');
    let deleteItemsModal = $('.js-restaurant-items__modal-delete-selected');
    let formAction = $(".js-restaurant-items__action");
    let formCategory = $(".js-restaurant-items__category");
    let itemsForm = $(".js-restaurant-items__form");
    let moveItemsModal = $('.js-restaurant-items__modal-move');
    let selectMoveToCategory = $('.js-restaurant-items-header__move-category');

    viewListButton.on('click', function () {
        if (!viewListButton.hasClass('active')) {
            viewListButton.addClass('active');
            viewBlocksButton.removeClass('active');
            itemsContent.removeClass('blocks').addClass('list');
        }
    });

    viewBlocksButton.on('click', function () {
        if (!viewBlocksButton.hasClass('active')) {
            viewBlocksButton.addClass('active');
            viewListButton.removeClass('active');
            itemsContent.removeClass('list').addClass('blocks');
        }
    });

    itemsContent.sortable({
        items: '> .js-restaurant-items-content__item',
        handle: ".js-restaurant-items-content__item--sort",
        update() {
            let data = $(this).sortable('serialize');

            $.ajax({
                data: data,
                type: "GET",
                url: "/restaurant/ajax/menu/items/order"
            })
        }
    });

    selectCategory.change(function () {
        $(this).closest('form').submit();
    });

    selectAllCheckbox.change(function () {
        if (this.checked) {
            itemCheckboxes.prop('checked', true);
        } else {
            itemCheckboxes.prop('checked', false);
        }

        checkMultipleActionButtons();
    });

    itemCheckboxes.change(function () {
        if (!this.checked) {
            selectAllCheckbox.prop('checked', false);
        }
        checkMultipleActionButtons();
    });

    removeItem.on('click', function () {
        let form = deleteItemModal.find('form');
        let item = $(this).closest('.js-restaurant-items-content__item');
        let itemName = item.find('.js-restaurant-items-content__item--name').attr('title');
        let itemId = item.data('item');
        form[0].reset();

        form.find('.js-restaurant-items__modal-delete--name').html(itemName);

        $(form).attr('action', '/restaurant/menu/items/' + itemId);

        deleteItemModal.modal(open);
    });

    removeItemsButton.click(function () {
        let itemsCount = $(".js-restaurant-items-content__item-select--checkbox:checked").length;
        let submitButton = deleteItemsModal.find('.js-restaurant-items__modal-delete--submit');

        deleteItemsModal.modal(open);

        submitButton.on('click', function () {
            formAction.val("delete");
            formCategory.val("");
            itemsForm.submit();
        })
    });

    selectMoveToCategory.on('change', function () {
        let itemsCount = $(".js-restaurant-items-content__item-select--checkbox:checked").length;
        let categoryId = $(this).val();
        let submitButton = moveItemsModal.find('.js-restaurant-items__modal-move--submit');

        moveItemsModal.modal(open);

        submitButton.click(function() {
            formAction.val("move");
            formCategory.val(categoryId);
            itemsForm.submit();
        })
    });

    function checkMultipleActionButtons() {
        if ($(".js-restaurant-items-content__item-select--checkbox:checked").length) {
            headerToolsPanel.addClass('active');
        } else {
            headerToolsPanel.removeClass('active');
        }
    }
}
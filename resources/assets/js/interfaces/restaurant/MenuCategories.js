export default function (options) {

    let $sortableCategories = $('.js-restaurant-categories__content');
    let $categoriesNames = $('.js-restaurant-categories__content-item-wrapper--name');
    let $categoryDeleteButton = $('.js-restaurant-categories__content-item-wrapper--delete-icon');
    let $deleteCategoryModal = $('.js-restaurant-categories__modal-delete');
    let $modalContent = $('.js-restaurant-categories__modal-delete--content');
    let $submitModalButton = $('.js-restaurant-categories__modal-delete--submit');

    updateCategoryCount();

    $sortableCategories.nestedSortable({
        listType: 'ul',
        // containment: ".restaurant-categories",
        axis: "y",
        handle: '.js-restaurant-categories__content-item-wrapper--icon',
        items: 'li',
        toleranceElement: '> div',

        relocate() {

            updateCategoryCount();

            let sortArray = $(this).nestedSortable('serialize');
            $.ajax({
                data: sortArray,
                type: "GET",
                url: "/restaurant/ajax/menu/categories/sort"
            })
        }
    });

    $categoriesNames.click(function() {
        $(this).closest('.js-restaurant-categories__content-item').toggleClass('active');
    });

    $categoryDeleteButton.click(function() {

        let $category = $(this).closest('.js-restaurant-categories__content-item-wrapper');
        let $form = $deleteCategoryModal.find('form');
        let categoryId = $category.data('category-id');
        let itemsCount = $category.data('items-count');
        let categoryName = $category.attr('title');
        let categoriesCount = $category.next().find('.js-restaurant-categories__content-item').length;

        if (itemsCount > 0) {
            $modalContent.html(options.msgContainsItems);
            $submitModalButton.hide();
        } else if (categoriesCount > 0) {
            $modalContent.html(options.msgContainsCategories);
            $submitModalButton.hide();
        } else {
            $modalContent.html(options.msgConfirmCategory + '<strong>' + categoryName + '</strong>?');
            $submitModalButton.show();
        }

        $form.attr("action", '/restaurant/menu/categories/' + categoryId);
        $deleteCategoryModal.modal(open);
    });

    function updateCategoryCount() {
        $sortableCategories.find('.js-restaurant-categories__content-item').each(function() {
            let $item = $(this);
            let $itemWrapper = $item.children('.js-restaurant-categories__content-item-wrapper');

            let itemsCount = $itemWrapper.next().children('.js-restaurant-categories__content-item').length;
            $itemWrapper.find('.js-restaurant-categories__content-item-wrapper--categories').text(itemsCount);
        });
    }
}
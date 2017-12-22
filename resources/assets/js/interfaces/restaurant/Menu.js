export default function () {

    let $restaurantMenu = $('.js-restaurant-menu'),
        $selectAllCategories = $restaurantMenu.find('.js-restaurant-menu__top-control--checkbox'),
        $categories = $restaurantMenu.find('.js-restaurant-menu__category'),
        $openCategoryButton = $restaurantMenu.find('.js-restaurant-menu__category-top-open'),
        $categoryItems = $restaurantMenu.find('.js-restaurant-menu__category-items'),
        $selectCategoryCheckboxes = $restaurantMenu.find('.js-restaurant-menu__category-top--checkbox'),
        $selectItemsCheckboxes = $restaurantMenu.find('.js-restaurant-menu__category-item--checkbox'),
        $selectGroupCheckboxes = $restaurantMenu.find('.js-restaurant-menu__group--checkbox'),
        $categoriesCount = $restaurantMenu.find('.js-restaurant-menu__categories-header--count'),
        $groupsCount = $restaurantMenu.find('.js-restaurant-menu__groups-header--count'),
        $itemsCount = $restaurantMenu.find('.js-restaurant-menu__category-top--count');

    setCategoriesCount();
    setGroupsCount();
    setItemsCount();

    // Select all categories on change of checkbox
    $selectAllCategories.change(selectAllCategories);

    $selectCategoryCheckboxes.change(selectCategory);

    $selectItemsCheckboxes.change(setItemsCount);

    $selectGroupCheckboxes.change(setGroupsCount);

    $openCategoryButton.click(toggleDropdownCategoryItems);

    function selectCategory() {

        let $category = $(this).closest('.js-restaurant-menu__category');
        let $innerItems = $category.find('.js-restaurant-menu__category-item--checkbox');

        if (this.checked) {
            $category.addClass('selected');
            $innerItems.prop('checked', true)
        } else {
            $category.removeClass('selected');
            $innerItems.prop('checked', false)
        }

        setCategoriesCount();
        setItemsCount();
    }

    function toggleDropdownCategoryItems() {

        let $this = $(this);

        let $thisItems = $this.closest('.js-restaurant-menu__category').find('.js-restaurant-menu__category-items');

        if(($this).hasClass('active')) {
            $this.toggleClass('active')
        } else {
            $openCategoryButton.removeClass('active');
            $this.addClass('active');
        }

        if ($thisItems.hasClass('active')) {
            $thisItems.toggleClass('active');
        } else {
            $categoryItems.removeClass('active');
            $thisItems.addClass('active');
        }
    }

    function selectAllCategories() {

        if (this.checked) {
            $categories.addClass('selected');
            $selectCategoryCheckboxes.prop('checked', true);
            $selectItemsCheckboxes.prop('checked', true);
        } else {
            $categories.removeClass('selected');
            $selectCategoryCheckboxes.prop('checked', false);
            $selectItemsCheckboxes.prop('checked', false);
        }
        setCategoriesCount();
        setItemsCount();
    }

    function setCategoriesCount() {
        let $checkedCount = $selectCategoryCheckboxes.filter(':checked').length;
        let $totalCount = $selectCategoryCheckboxes.length;

        $categoriesCount.text('(' + $checkedCount + '/' + $totalCount + ')');
    }

    function setItemsCount() {
        $itemsCount.each(function() {

            let $this = $(this),
                $category = $this.closest('.js-restaurant-menu__category'),
                $categoryCheckbox = $category.find('.js-restaurant-menu__category-top--checkbox'),
                $items = $category.find('.js-restaurant-menu__category-item--checkbox'),
                itemsCount = $items.filter(':checked').length,
                itemsTotal = $items.length;

            if(itemsCount > 0) {
                $category.addClass('selected');
                $categoryCheckbox.prop('checked', true);
            } else {
                $category.removeClass('selected');
                $categoryCheckbox.prop('checked', false);
            }

            $(this).text('(' + itemsCount + '/' + itemsTotal + ')');
        });
    }

    function setGroupsCount() {
        let $checkedCount = $selectGroupCheckboxes.filter(':checked').length;
        let $totalCount = $selectGroupCheckboxes.length;

        $groupsCount.text('(' + $checkedCount + '/' + $totalCount + ')');
    }

}
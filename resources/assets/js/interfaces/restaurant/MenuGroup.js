export default function (options) {

    let $menuGroup = $('.js-restaurant-group'),
        $menuGroupLanguagesModal = $('.js-restaurant-group__modal-languages'),
        $closeModalButton = $('.js-restaurant-group__modal-languages--submit'),
        $contentBlock = $menuGroup.find('.js-restaurant-group__content'),
        $openLanguages = $menuGroup.find('.js-restaurant-group__top-link'),
        $createCourseButton = $menuGroup.find('.js-restaurant-group__create-link'),
        $courses = $menuGroup.find('.js-restaurant-group__content-courses'),
        $course = $menuGroup.find('.js-restaurant-group__course'),
        $items = $menuGroup.find('.js-restaurant-group__category-content-item'),
        $categorySelect = $menuGroup.find('.js-restaurant-group__category-content--select'),
        $addItemButton = $menuGroup.find('.js-restaurant-group__category-content-item--add'),
        $removeItemButton = $menuGroup.find(),
        $removeCourseButton = $menuGroup.find('.js-restaurant-group__course-header--remove'),
        selectedCategory = '',
        $newCourseActiveElement = $(options.newCourseActiveElement),
        $newItemElement = $(options.newItemElement);

    initListeners();

    function initListeners() {
        $openLanguages.click(function () {
            $menuGroupLanguagesModal.modal(open);
        });

        $closeModalButton.click(function () {
            $menuGroupLanguagesModal.modal('hide');
        });

        $categorySelect.change(displayCategoryItems);

        $createCourseButton.click(createNewCourse);

        $addItemButton.click(insertItem);

        $courses.on('click', '.js-restaurant-group__course-header-link', toggleCourses);

        // Remove course totally
        $courses.on('click', '.js-restaurant-group__course-header--remove', removeCourse);
        // $removeCourseButton.click(removeCourse);

        // Remove item from menu on cross click
        $courses.on('click', '.js-restaurant-group__course-content-item--remove', removeItem);
        // $removeItemButton.click(removeItem);

    }

    function refreshListeners() {

        if (!$menuGroup.find('.js-restaurant-group__course').length) {
            $contentBlock.removeClass('active');
        }
    }

    function removeItem() {
        $(this).closest('.js-restaurant-group__course-content-item').remove();
    }

    function removeCourse() {
        $(this).closest('.js-restaurant-group__course').remove();
        renderCourseNumbers();
    }

    // Create new new course with active class
    function createNewCourse() {

        if (!$contentBlock.filter('active').length) {
            $contentBlock.addClass('active');
        }

        $course.removeClass('active');
        $courses.append($newCourseActiveElement.clone());
        renderCourseNumbers();
    }

    // Render course number, give right order for courses
    function renderCourseNumbers() {
        let courseIterator = 1;
        $course = $menuGroup.find('.js-restaurant-group__course');
        $course.each(function () {
            let $this = $(this);
            // Change course number on item input
            $this.find('.js-restaurant-group__course-content-item--hidden').attr('name', 'items[' + courseIterator + '][]');
            // Change course number on course quantity
            $this.find('.js-restaurant-group__course-content--quantity').attr('name', 'quantities[' + courseIterator + ']');
            $this.find('.js-restaurant-group__course-header--number').text(courseIterator++)
        });
        refreshListeners();
    }

    // Insert item to course
    function insertItem() {

        let $thisItem = $(this).closest('.js-restaurant-group__category-content-item'),
            $activeCourse = $menuGroup.find('.js-restaurant-group__course.active'),
            itemId = $thisItem.data('item-id'),
            itemName = $thisItem.find('.js-restaurant-group__category-content-item--name').attr('title'),
            $itemElement = $newItemElement.clone();

        //check if item is already added to active course
        if ($activeCourse.find('.js-restaurant-group__course-content-item--hidden[value="' + itemId + '"]').length > 0) {
            alert(options.itemExistsMsg);
            return;
        }

        $itemElement.find('.js-restaurant-group__course-content-item--hidden').val(itemId);
        $itemElement.find('.js-restaurant-group__course-content-item--name').text(itemName);

        if ($activeCourse.length) {
            // Append item to course if one is active
            $activeCourse.find('.js-restaurant-group__course-content-items').append($itemElement);
        } else {
            // Create course and append item to it
            createNewCourse();
            $menuGroup.find('.js-restaurant-group__course.active .js-restaurant-group__course-content-items').append($itemElement);
        }
        renderCourseNumbers();
    }

    function displayCategoryItems() {
        let $thisCategoryId = $(this).val();

        if ($thisCategoryId !== selectedCategory) {
            if (!$thisCategoryId) {
                $items.show();
            } else {
                $items.hide();
                $items.filter('[data-category-id="' + $thisCategoryId + '"]').show();
            }
        }
        selectedCategory = $thisCategoryId;
    }

    function toggleCourses() {
        let $thisCourse = $(this).closest('.js-restaurant-group__course');
        if ($thisCourse.hasClass('active')) {
            $thisCourse.toggleClass('active');
        } else {
            $course.removeClass('active');
            $thisCourse.addClass('active');
        }
    }
}
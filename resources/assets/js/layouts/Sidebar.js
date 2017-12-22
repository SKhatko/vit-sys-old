export default function () {

    let $sidebar = $('.sidebar');
    let $navCategoryLink = $('.sidebar-nav__menu-item--link');
    let $navItems = $('.sidebar-nav__menu-item');

    $('.js-sidebar-toggle__button').on('click', function () {
        $sidebar.toggleClass('sidebar--active');
    });

    $navCategoryLink.click(activateSidebarNavigation);


    function activateSidebarNavigation() {
        let $thisItem = $(this).parent();

        if ($thisItem.hasClass('active')) {
            $navItems.removeClass('active');
        } else {
            $navItems.removeClass('active');
            $thisItem.addClass('active');
        }
    }

    function activateSideBarMenu(page) {
        let $activeMenu = $navItems.filter('[data-page="' + page + '"]');
        if ($activeMenu.length) {
            $activeMenu.addClass('active current')
        } else {
            let $activeSubMenu = $navItems.find('.sidebar-nav__submenu-item[data-page="' + page + '"]');
            $activeSubMenu.addClass('current');
            $activeSubMenu.closest('.sidebar-nav__menu-item').addClass('active current');
        }
    }

    return {
        activateSideBarMenu: activateSideBarMenu,
    }
}

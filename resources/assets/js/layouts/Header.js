export default function Header() {

    let $profile = $('.header-profile');

    // Logout form submit
    $('.js-logout__link').click(function () {
        $('.js-logout__form').submit();
    });

    // Print page button
    $('.js-header-print__button').click(function () {
        window.print();
    });

}

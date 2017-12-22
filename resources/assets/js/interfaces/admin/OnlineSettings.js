export default function () {
    let onlineLinkInput = $('.js-admin-online__footer-link-input');
    let copyLinkButton = $('.js-admin-online__footer--copy');

    copyLinkButton.click(function() {
        onlineLinkInput.select();
        document.execCommand("copy");
    });


}

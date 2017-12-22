export default function () {

    let $createClientButton = $('.js-clients-content__create-link');
    let $createClientModal = $('.js-clients__modal-create');


    $createClientButton.click(function() {
        $createClientModal.modal(open);
    })

}
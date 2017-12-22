export default function () {

    let $editClientButton = $('.js-client-info__client--edit-icon');
    let $editClientModal = $('.js-client__modal-edit');

    $editClientButton.click(function() {
        $editClientModal.modal(open);
    })

}
export default function () {

    let $editCompanyButton = $('.js-companies-content__company--edit-icon');
    let $editCompanyModal = $('.js-companies__modal-edit');
    let $companyNameInput = $('.js-companies__modal-edit--name');
    let $editFormInModal = $editCompanyModal.find('form')

    $editCompanyButton.click(function () {
        let $company = $(this).closest('.js-companies-content__company');
        let $companyId = $company.data('company-id');
        let $companyName = $company.data('company-name');

        $companyNameInput.val($companyName);

        $editFormInModal.attr('action', '/crm/companies/' + $companyId);

        $editCompanyModal.modal(open);
    });
}
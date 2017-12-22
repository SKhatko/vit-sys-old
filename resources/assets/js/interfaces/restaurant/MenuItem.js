export default function (options) {

    let $languagesModal = $('.js-restaurant-item__modal-languages');
    let $languagesLink = $('.js-restaurant-item__footer-languages-link');
    let $submitModal = $('.js-restaurant-item__modal-languages--submit');
    let $removeImageButton = $('.js-restaurant-item__content-data-img--remove');
    let $itemImage = $('.restaurant-item__content-data-img--image');
    let $itemImageName = $('.js-restaurant-item__content-data-img--name');
    let $foodAllergiesCount = $('.js-restaurant-item__content-food-header--count');
    let $drinkAllergiesCount = $('.js-restaurant-item__content-drink-header--count');
    let $foodAllergiesCheckboxes = $('.js-restaurant-item__content-food-content--select-checkbox');
    let $drinkAllergiesCheckboxes = $('.js-restaurant-item__content-drink-content--select-checkbox');

    $foodAllergiesCount.text(' ( '+ $foodAllergiesCheckboxes.filter(":checked").length + '/' + $foodAllergiesCheckboxes.length + ' )');
    $drinkAllergiesCount.text(' ( '+ $drinkAllergiesCheckboxes.filter(":checked").length + '/' + $drinkAllergiesCheckboxes.length + ' )');

    $foodAllergiesCheckboxes.change(function() {
        $foodAllergiesCount.text(' ( '+ $('.js-restaurant-item__content-food-content--select-checkbox:checked').length + '/' + $foodAllergiesCheckboxes.length + ' )');
    });

    $drinkAllergiesCheckboxes.change(function() {
        $drinkAllergiesCount.text(' ( '+ $('.js-restaurant-item__content-drink-content--select-checkbox:checked').length + '/' + $drinkAllergiesCheckboxes.length + ' )');
    });

    $languagesLink.click(function() {
        $languagesModal.modal(open);
    });

    $submitModal.click(function() {
        $languagesModal.modal('hide');
    });

    let cropperOptions = {
        csrf_token: options.csrf_token,
        uploadUrl: '/restaurant/menu/items/upload',
        cropUrl: '/restaurant/menu/items/crop',
        outputUrlId: options.outputUrlId,
        modal: true,
        loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
        customUploadButtonId:'cropic-upload-button',
        doubleZoomControls: false,
    };

    new Croppic('croppic-image', cropperOptions);

    $removeImageButton.click(deleteItemImage);

    function deleteItemImage() {
        $itemImageName.val("");
        $itemImage.attr('src' , options.noPhotoPath);
        $removeImageButton.remove();
    }

}
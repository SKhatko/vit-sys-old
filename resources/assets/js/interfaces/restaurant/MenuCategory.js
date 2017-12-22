export default function (options) {

    let $removeImageButton = $('.js-restaurant-category__content-data-img--remove');
    let $categoryImage = $('.restaurant-category__content-data-img--image');
    let $categoryImageName = $('.js-restaurant-category__content-data-img--name');

    let cropperOptions = {
        csrf_token: options.csrf_token,
        uploadUrl: '/restaurant/menu/categories/upload',
        cropUrl: '/restaurant/menu/categories/crop',
        outputUrlId: options.outputUrlId,
        modal: true,
        loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
        customUploadButtonId:'cropic-upload-button',
        doubleZoomControls: false,
    };

    new Croppic('croppic-image', cropperOptions);

    $removeImageButton.click(deleteCategoryImage);

    function deleteCategoryImage() {
        $categoryImageName.val("");
        $categoryImage.attr('src' , options.noPhotoPath);
        $removeImageButton.remove();
    }

}
export default class Auth {

    constructor(options) {

        // Toggle input login validation classes
        if($('.validation:visible').length) {

            let authInputs = $('.js-auth-form__input, .auth-form__label');
            authInputs.addClass('invalid');

            authInputs.on('change', function() {
                authInputs.removeClass('invalid');
                $('.validation').hide();
            })
        }
    }


}

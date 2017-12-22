export default function Header() {

    let $alert = $('.js-alert');

    $alert.modal(open);

    setTimeout(function(){
        $alert.modal('hide')
    }, 4000);
}

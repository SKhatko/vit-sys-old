export default function () {
    $('[title]')
        .tooltip({
            delay: { "show": 1000 },
            container: 'body'
        })
        .on('show.bs.tooltip', function () {
            $('.tooltip').not(this).hide();
        });
}
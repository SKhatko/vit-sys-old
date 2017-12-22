
window.$ = window.jQuery = require('jquery');

window.moment = require ('moment');

window.highcharts = window.Highcharts = require ('highcharts');


require ('bootstrap-sass');

require ('bootstrap-datepicker');

require ('bootstrap-datepicker/dist/locales/bootstrap-datepicker.de.min');

require ('./components/jquery.tableM');

require ('./vendors/croppic');

require ('jquery-ui/ui/widgets/autocomplete');

require ('jquery-ui/ui/widgets/sortable');

require ('jquery-ui/ui/widgets/draggable');

require ('jquery-ui/ui/widgets/resizable');

require ('./vendors/jquery.mjs.nestedSortable');

require('./vendors/tabs.js');

require('./vendors/owl.carousel');

require ('jquery-timepicker/jquery.timepicker');

require ('datatables.net/js/jquery.dataTables');


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'xsrfHeaderName': $('meta[name="csrf-token"]').attr('content')
};

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from "laravel-echo"

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
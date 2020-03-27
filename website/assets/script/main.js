require('bootstrap');
require('./highlight');
require('../style/main.scss');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

jQuery = require('jquery');

(function($) {

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

})(jQuery);


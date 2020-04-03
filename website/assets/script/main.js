require('./bootstrap');
require('./code');
require('../style/main.scss');

document.getElementById("sidebarCollapse").addEventListener("click", function(){
    document.getElementById("sidebar").classList.toggle("active");
});

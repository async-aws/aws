
// The toggle button
document.getElementById("sidebarCollapse").addEventListener("click", function(){
    document.getElementById("sidebar").classList.toggle("active");
});

let isUlHidden = function (ul) {
    return !ul.classList.contains('show');
};


// The submenu
let elements = document.getElementsByClassName('dropdown-toggle');
for (let i = 0; i < elements.length; i++) {
    elements[i].addEventListener("click", function(event){
        let link = el = elements[i];
        let ul = undefined;

        while (el = el.nextSibling ) {
            if (el.nodeName.toLowerCase() == 'ul') {
                ul = el;
                break;
            }
        }

        if (ul === undefined) {
            // No UL found
            return;
        }

        // Start the toggle
        if (isUlHidden(ul)) {
            ul.classList.add("show");
            link.classList.remove("collapsed");
            link.setAttribute('aria-expanded', true);
        } else {
            ul.classList.remove("show");
            link.classList.add("collapsed");
            link.setAttribute('aria-expanded', false);
        }
    });
}



// The toggle button
document.getElementById("sidebarCollapse").addEventListener("click", function(){
    document.getElementById("sidebar").classList.toggle("active");
});

let isUlHidden = function (ul) {
    return !ul.classList.contains('show');
};


// The submenu
const elements = document.getElementsByClassName('dropdown-toggle');
for (const element of elements) {
    element.addEventListener("click", () => {
        let el = element;
        let ul = undefined;

        while (el = el.nextSibling ) {
            if (el.nodeName.toLowerCase() === 'ul') {
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
            element.classList.remove("collapsed");
            element.setAttribute('aria-expanded', true);
        } else {
            ul.classList.remove("show");
            element.classList.add("collapsed");
            element.setAttribute('aria-expanded', false);
        }
    });
}


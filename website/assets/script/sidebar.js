// The toggle button
document.getElementById('sidebarCollapse').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('active');
});

const isUlHidden = (ul) => {
    return !ul.classList.contains('show');
};

// The submenu
for (const element of document.getElementsByClassName('dropdown-toggle')) {
    element.addEventListener('click', () => {
        let el = element;
        let ul = undefined;

        while (el = el.nextSibling) {
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
            ul.classList.add('show');
            element.classList.remove('collapsed');
            element.setAttribute('aria-expanded', true);
        } else {
            ul.classList.remove('show');
            element.classList.add('collapsed');
            element.setAttribute('aria-expanded', false);
        }
    });
}


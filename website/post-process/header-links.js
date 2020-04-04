module.exports = function(dom) {
    dom.window.document.querySelectorAll('h2, h3').forEach((headline) => {

        let id = headline.getAttribute('id');
        let link = dom.window.document.createElement('a');
        link.setAttribute('href', '#' + id);
        link.setAttribute('title', 'Permalink to this headline');
        link.setAttribute('class', 'headerlink');
        link.innerHTML = '¶';
        headline.innerHTML = headline.innerHTML + link.outerHTML;
    });
};


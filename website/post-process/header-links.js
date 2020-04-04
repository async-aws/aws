module.exports = function(dom) {
    for(const headline of dom.window.document.querySelectorAll('h2, h3')) {
        const id = headline.getAttribute('id');
        const link = dom.window.document.createElement('a');

        link.setAttribute('href', '#' + id);
        link.setAttribute('title', 'Permalink to this headline');
        link.setAttribute('class', 'headerlink');
        link.innerHTML = '¶';
        headline.append(link);
    }
};

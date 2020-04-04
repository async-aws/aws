module.exports = function (dom) {
    for (const elem of dom.window.document.querySelectorAll('code.hljs:not(.language-text,.language-diff)')) {
        const button = dom.window.document.createElement('div');
        button.classList.add('btn-copy');
        button.title = 'copy to clipboard';
        elem.prepend(button);
    }
};

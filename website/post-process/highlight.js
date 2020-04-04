const highlight = require('highlight.js/lib/highlight');

highlight.registerLanguage('php', require('highlight.js/lib/languages/php'));
highlight.registerLanguage('ini', require('highlight.js/lib/languages/ini'));
highlight.registerLanguage('diff', require('highlight.js/lib/languages/diff'));
highlight.registerLanguage('shell', require('highlight.js/lib/languages/bash'));
highlight.registerLanguage('yaml', require('highlight.js/lib/languages/yaml'));

module.exports = dom => {
    for (const block of dom.window.document.querySelectorAll('pre code')) {
        if (!block.classList.contains('hljs')) {
            highlight.highlightBlock(block);
        }
    }
};

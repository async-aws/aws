const fs = require('fs');
const jsdom = require("jsdom");
const { JSDOM } = jsdom;
const highlight = require('highlight.js/lib/highlight');

highlight.registerLanguage('php', require('highlight.js/lib/languages/php'));
highlight.registerLanguage('ini', require('highlight.js/lib/languages/ini'));
highlight.registerLanguage('diff', require('highlight.js/lib/languages/diff'));
highlight.registerLanguage('shell', require('highlight.js/lib/languages/bash'));
highlight.registerLanguage('yaml', require('highlight.js/lib/languages/yaml'));


// Create a list of HTML files and run highlight.js on them.
module.exports = function(dom) {
    dom.window.document.querySelectorAll('pre code').forEach((block) => {
        if (!block.classList.contains('hljs')) {
            highlight.highlightBlock(block);
        }
    });
};


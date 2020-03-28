import highlight from 'highlight.js/lib/highlight';

highlight.registerLanguage('php', require('highlight.js/lib/languages/php'));
highlight.registerLanguage('ini', require('highlight.js/lib/languages/ini'));
highlight.registerLanguage('diff', require('highlight.js/lib/languages/diff'));
highlight.registerLanguage('shell', require('highlight.js/lib/languages/shell'));
highlight.registerLanguage('yaml', require('highlight.js/lib/languages/yaml'));

document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('pre code').forEach((block) => {
        highlight.highlightBlock(block);
    });
});

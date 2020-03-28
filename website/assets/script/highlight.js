
import highlight from 'highlight.js/lib/highlight';
import languagePHP from 'highlight.js/lib/languages/php';
import languageIni from 'highlight.js/lib/languages/ini';
import languageBash from 'highlight.js/lib/languages/bash';
import languageDiff from 'highlight.js/lib/languages/diff';

highlight.registerLanguage('php', languagePHP);
highlight.registerLanguage('ini', languageIni);
highlight.registerLanguage('bash', languageBash);
highlight.registerLanguage('diff', languageDiff);

document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('pre code').forEach((block) => {
        highlight.highlightBlock(block);
    });
});

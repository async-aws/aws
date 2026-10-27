import highlight from 'highlight.js/lib/core';
import php from 'highlight.js/lib/languages/php';
import ini from 'highlight.js/lib/languages/ini';
import diff from 'highlight.js/lib/languages/diff';
import bash from 'highlight.js/lib/languages/bash';
import yaml from 'highlight.js/lib/languages/yaml';

highlight.registerLanguage('php', php);
highlight.registerLanguage('ini', ini);
highlight.registerLanguage('diff', diff);
highlight.registerLanguage('shell', bash);
highlight.registerLanguage('yaml', yaml);

export default dom => {
    for (const block of dom.window.document.querySelectorAll('pre code')) {
        if (!block.classList.contains('hljs')) {
            highlight.highlightElement(block);
        }
    }
};

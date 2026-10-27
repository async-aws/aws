import fs from 'fs';
import { JSDOM } from 'jsdom';
import walk from './walk.js';

import highlight from './highlight.js';
import headerLinks from './header-links.js';
import copyCode from './copy-code.js';

const outputDir = process.argv[2];
console.log('Dir: ', outputDir);

walk(outputDir, (err, files) => {
    if (err) throw err;
    files
        .filter(file => file.substr(-5) === '.html')
        .forEach(file => {
            JSDOM.fromFile(file, {}).then(dom => {
                highlight(dom);
                headerLinks(dom);
                copyCode(dom);

                // Only write the file once
                fs.writeFile(file, dom.serialize(), err => {
                    if (err) return console.log(err);
                    console.log('Done: '+file);
                });
            });
        });
});

console.log('Done');

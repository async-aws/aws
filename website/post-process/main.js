const fs = require('fs');
const jsdom = require('jsdom');
const { JSDOM } = jsdom;
const walk = require('./walk');

const highlight = require('./highlight')
const headerLinks = require('./header-links')

const outputDir = process.argv[2];
console.log('Dir: ', outputDir);

walk(outputDir, (err, files) => {
    if (err) throw err;
    files
        .filter(file => file.substr(-5) === '.html')
        .forEach(file => {
            JSDOM.fromFile(file, {}).then(dom => {
                // Run our post processors
                highlight(dom);
                headerLinks(dom);

                // Only write the file once
                fs.writeFile(file, dom.serialize(), err => {
                    if (err) return console.log(err);
                    console.log('Done: '+file);
                });
            });
        });
});

console.log('Done');

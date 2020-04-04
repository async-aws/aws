const fs = require('fs');
const jsdom = require('jsdom');
const { JSDOM } = jsdom;
const walk = require('./walk');

const highlight = require('./highlight')
const headerLinks = require('./header-links')

const outputDir = process.argv[2];
console.log('Dir: ', outputDir);

walk(outputDir, function (err, files) {
    if (err) throw err;
    files
        .filter(function (file) {
            return file.substr(-5) === '.html';
        })
        .forEach(function (file) {
            JSDOM.fromFile(file, {}).then(dom => {
                // Run our post processors
                highlight(dom);
                headerLinks(dom);

                // Only write the file once
                fs.writeFile(file, dom.serialize(), function (err) {
                    if (err) return console.log(err);
                    console.log('Done: '+file);
                });
            });
        });
});

console.log('Done');

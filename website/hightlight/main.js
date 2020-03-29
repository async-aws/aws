const fs = require('fs');
const path = require('path');
const jsdom = require("jsdom");
const { JSDOM } = jsdom;
const highlight = require('highlight.js/lib/highlight');

highlight.registerLanguage('php', require('highlight.js/lib/languages/php'));
highlight.registerLanguage('ini', require('highlight.js/lib/languages/ini'));
highlight.registerLanguage('diff', require('highlight.js/lib/languages/diff'));
highlight.registerLanguage('shell', require('highlight.js/lib/languages/shell'));
highlight.registerLanguage('yaml', require('highlight.js/lib/languages/yaml'));


const outputDir = process.argv[2];
console.log('Dir: ', outputDir);

/**
 * Read directory and all its contents
 * @see https://stackoverflow.com/a/5827895/1526789
 */
var walk = function(dir, done) {
    var results = [];
    fs.readdir(dir, function(err, list) {
        if (err) return done(err);
        var pending = list.length;
        if (!pending) return done(null, results);
        list.forEach(function(file) {
            file = path.resolve(dir, file);
            fs.stat(file, function(err, stat) {
                if (stat && stat.isDirectory()) {
                    walk(file, function(err, res) {
                        results = results.concat(res);
                        if (!--pending) done(null, results);
                    });
                } else {
                    results.push(file);
                    if (!--pending) done(null, results);
                }
            });
        });
    });
};

// Create a list of HTML files and run highlight.js on them.
walk(outputDir, function(err, files) {
    if (err) throw err;
    files
        .filter(function(file) {
            return file.substr(-5) === '.html';
        })
        .forEach(function(file) {
            JSDOM.fromFile(file, {}).then(dom => {
                dom.window.document.querySelectorAll('pre code').forEach((block) => {
                    highlight.highlightBlock(block);
                });

                fs.writeFile(file, dom.serialize(), function (err) {
                    if (err) return console.log(err);
                    console.log(file);
                });
            });
        });
});


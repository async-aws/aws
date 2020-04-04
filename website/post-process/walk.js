const fs = require('fs');
const path = require('path');

/**
 * Read directory for files.
 * @see https://stackoverflow.com/a/5827895/1526789
 */
const walk = function(dir, done) {
    var results = [];
    fs.readdir(dir, function(err, list) {
        if (err) return done(err);
        let pending = list.length;
        if (!pending) return done(null, results);
        for(const filePath of list) {
            const file = path.resolve(dir, filePath);
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
        };
    });
};

module.exports = walk;

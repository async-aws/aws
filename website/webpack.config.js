var Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('template/assets/')
    // public path used by the web server to access the output path
    .setPublicPath('/assets')
    .addEntry('app', './assets/script/main.js')

    .copyFiles({
        from: './assets/image',

        // if versioning is enabled, add the file hash too
        to: 'image/[path][name].[hash:8].[ext]',

        // only copy files matching this pattern
        pattern: /\.(png|jpg|jpeg|svg|webmanifest)$/
    })

    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })


    .enableSassLoader()
    .enablePostCssLoader()
    .splitEntryChunks()
    .configureSplitChunks(function (splitChunks) {
        // https://webpack.js.org/plugins/split-chunks-plugin/
        //splitChunks.name = !Encore.isProduction();
        splitChunks.cacheGroups = {
            vendors: {
                test: /[\\/]node_modules[\\/]|bootstrap.(js|scss)/
            }
        }
    })


    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();

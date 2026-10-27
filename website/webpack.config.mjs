import Encore from '@symfony/webpack-encore';

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.mjs file.
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
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // polyfills the features used by the code, based on browserslist
    .configureBabel((config) => {
        config.plugins.push(['babel-plugin-polyfill-corejs3', { method: 'usage-global' }]);
    })

    .configureCssMinimizerPlugin((options, MinimizerPlugin) => {
        options.minify = MinimizerPlugin.cssnanoMinify;
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
;

if (Encore.isProduction()) {
    Encore.cleanupOutputBeforeBuild()
}

export default await Encore.getWebpackConfig();

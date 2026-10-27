import Encore from '@symfony/webpack-encore';

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('template/assets/')
    .setPublicPath('/assets')
    .addEntry('app', './assets/script/main.js')

    .copyFiles({
        from: './assets/image',
        to: 'image/[path][name].[hash:8].[ext]',
        pattern: /\.(png|jpg|jpeg|svg|webmanifest)$/
    })

    .disableSingleRuntimeChunk()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .enableSassLoader((options) => {
        options.sassOptions.quietDeps = true;
        // Bootstrap 5 does not support the Sass module system yet
        options.sassOptions.silenceDeprecations = ['import'];
    })
    .enablePostCssLoader()
    .configureCssMinimizerPlugin((options, MinimizerPlugin) => {
        options.minify = MinimizerPlugin.cssnanoMinify;
    })
    .splitEntryChunks()
    .configureSplitChunks(function (splitChunks) {
        splitChunks.cacheGroups = {
            vendors: {
                test: /[\\/]node_modules[\\/]|bootstrap.(js|scss)/
            }
        }
    })
;

if (Encore.isProduction()) {
    Encore.cleanupOutputBeforeBuild((options) => {
        options.keep = '.gitignore';
    });
}

export default await Encore.getWebpackConfig();

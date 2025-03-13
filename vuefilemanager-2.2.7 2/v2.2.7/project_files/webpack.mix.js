const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .js('resources/js/main.js', 'public/js')
    .vue({
        options: {
            compilerOptions: {
                isCustomElement: tag => tag.startsWith('ion-')
            }
        }
    })
    .sass('resources/sass/app.scss', 'public/css/app.css', {
        implementation: require('sass'),
        sassOptions: {
            fiber: false,
        }
    })
    .postCss("resources/css/tailwind.css", "public/css/tailwind.css", [
        require("tailwindcss"),
        require('autoprefixer'),
        require('cssnano')({
            preset: ['default', {
                discardComments: {
                    removeAll: true,
                },
            }]
        })
    ])
    .webpackConfig({
        output: {
            chunkFilename: 'js/[name].[chunkhash].js',
            publicPath: '/',
        },
        optimization: {
            splitChunks: {
                chunks: 'all',
                minSize: 20000,
                maxSize: 250000,
                minChunks: 1,
                maxAsyncRequests: 30,
                maxInitialRequests: 30,
                automaticNameDelimiter: '~',
                enforceSizeThreshold: 50000,
                cacheGroups: {
                    defaultVendors: {
                        test: /[\\/]node_modules[\\/]/,
                        priority: -10,
                        reuseExistingChunk: true,
                    },
                    default: {
                        minChunks: 2,
                        priority: -20,
                        reuseExistingChunk: true,
                    },
                },
            },
            minimize: mix.inProduction(),
            minimizer: [
                (compiler) => {
                    const TerserPlugin = require('terser-webpack-plugin');
                    new TerserPlugin({
                        terserOptions: {
                            compress: {
                                drop_console: mix.inProduction(),
                                drop_debugger: mix.inProduction()
                            },
                            output: {
                                comments: false
                            }
                        }
                    }).apply(compiler);
                }
            ],
        },
        resolve: {
            alias: {
                '@': path.resolve('resources/js'),
                '@components': path.resolve('resources/js/components'),
                '@assets': path.resolve('resources/assets')
            },
            extensions: ['*', '.js', '.jsx', '.vue', '.ts', '.tsx']
        },
        module: {
            rules: [
                {
                    test: /\.tsx?$/,
                    loader: "ts-loader",
                    options: { appendTsSuffixTo: [/\.vue$/] }
                }
            ]
        }
    })
    .extract(['vue', 'vuex', 'vue-router'])
    .version()
    .sourceMaps(false, 'source-map')
    .disableNotifications();

// 生产环境特定配置
if (mix.inProduction()) {
    mix.options({
        clearConsole: true,
        terser: {
            terserOptions: {
                compress: {
                    drop_console: true
                }
            }
        },
        cssNano: {
            discardComments: {
                removeAll: true
            }
        }
    });
}

// 开发环境特定配置
if (!mix.inProduction()) {
    mix.webpackConfig({
        devtool: 'inline-source-map'
    })
    .options({
        hmrOptions: {
            host: 'localhost',
            port: 8080
        }
    });
}

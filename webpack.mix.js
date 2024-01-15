let mix = require('laravel-mix');

/*
* CKEditor Config
*/

const CKEditorWebpackPlugin = require('@ckeditor/ckeditor5-dev-webpack-plugin');
const {styles} = require('@ckeditor/ckeditor5-dev-utils');

const CKERegex = {
  svg: /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/,
  css: /ckeditor5-[^/\\]+[/\\]theme[/\\].+\.css$/,
};

Mix.listen('configReady', webpackConfig => {
  const rules = webpackConfig.module.rules;

  const targetSVG = /(\.(png|jpe?g|gif|webp|avif)$|^((?!font).)*\.svg$)/;
  const targetFont = /(\.(woff2?|ttf|eot|otf)$|font.*\.svg$)/;
  const targetCSS = /\.p?css$/;

  for (let rule of rules) {
    if (rule.test.toString() === targetSVG.toString()) {
      rule.exclude = CKERegex.svg;
    } else if (rule.test.toString() === targetFont.toString()) {
      rule.exclude = CKERegex.svg;
    } else if (rule.test.toString() === targetCSS.toString()) {
      rule.exclude = CKERegex.css;
    }
  }
});

mix.webpackConfig({
  plugins: [
    new CKEditorWebpackPlugin({
      language: 'en',
      addMainLanguageTranslationsToAllAssets: true
    })
  ],
  module: {
    rules: [
      {
        test: CKERegex.svg,
        use: ['raw-loader']
      },
      {
        test: CKERegex.css,
        use: [
          {
            loader: 'style-loader',
            options: {
              injectType: 'singletonStyleTag',
              attributes: {
                'data-cke': true
              }
            }
          },
          'css-loader',
          {
            loader: 'postcss-loader',
            options: {
              postcssOptions: styles.getPostCssConfig({
                themeImporter: {
                  themePath: require.resolve('@ckeditor/ckeditor5-theme-lark')
                },
                minify: true
              })
            }
          }
        ]
      }
    ]
  }
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath('public')
   .js('resources/assets/js/app.js', 'js')
   .js('resources/assets/js/bootstrap.js', 'js')
   .js('resources/assets/js/event_view.js', 'js')
   .js('resources/assets/js/filebrowser.js', 'js')
   .js('resources/assets/js/helpers.js', 'js')
   .js('resources/assets/js/pilots_guide.js', 'js')
   .js('resources/assets/js/realops.js', 'js')
   .js('resources/assets/js/snowstorm.js', 'js')
   .sass('resources/assets/sass/app.scss', 'css')
   .sass('resources/assets/sass/clipboard.scss', 'css')
   .sass('resources/assets/sass/Footer-white.scss', 'css')
   .sass('resources/assets/sass/home.scss', 'css')
   .sass('resources/assets/sass/main.scss', 'css')
   .sass('resources/assets/sass/pilots_guide.scss', 'css');

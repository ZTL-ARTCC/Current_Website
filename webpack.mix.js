let mix = require('laravel-mix');

/*
* CKEditor Config
*/

const { CKEditorTranslationsPlugin } = require( '@ckeditor/ckeditor5-dev-translations' );
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
    new CKEditorTranslationsPlugin({
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

mix.version(); // Version all compiled assets - autobusts cache!

mix.setPublicPath('public')
   .js('resources/assets/js/app.js', 'js')
   .sass('resources/assets/sass/app.scss', 'css');

const fs = require("fs");
fs.readdirSync("resources/assets/js/mix/").forEach(fileName => mix.js(`resources/assets/js/mix/${fileName}`, "public/js"));
fs.readdirSync("resources/assets/sass/mix/").forEach(fileName => mix.sass(`resources/assets/sass/mix/${fileName}`, "public/css"));

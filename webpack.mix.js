const mix = require('laravel-mix');

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

mix.js('resources/js/color-modes.js', 'public/js/color-modes.js')
    .copy('resources/img/*', 'public/img/')
    .copy('resources/img/platforms/*', 'public/img/platforms/')
    /*.postCss('resources/css/app.css', 'public/assets/css/custom.css', [
    ])*/;

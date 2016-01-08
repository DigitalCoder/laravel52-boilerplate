var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {
    "use strict";
    var bowerDir = './bower_components/';
    var assetsDir = './resources/assets';

    mix
        /* Site-specific CSS */
        .sass(['**/*.scss'], 'public/css/app.css', assetsDir + '/sass')
        /* Vendor CSS, mainly from bower components */
        .styles(
            [
                'bootstrap/dist/css/bootstrap.css',
                'font-awesome/css/font-awesome.min.css'
            ],
            'public/css/vendor.css',
            bowerDir
        )
        /* Site-specific JavaScript */
        .scripts(['**/*.js'], 'public/js/app.js', assetsDir + '/js')
        /* Vendor JavaScript, mainly from bower components */
        .scripts(
            [
                'jquery/dist/jquery.min.js',
                'bootstrap/dist/js/bootstrap.min.js',
                'bootbox.js/bootbox.js'
            ],
            'public/js/vendor.js',
            bowerDir
        )
        /* Image Files */
        .copy(
            'resources/assets/images',
            'public/images'
        )
        .copy(
            'resources/favicons/',
            'public'
        )
        /* Perform file versioning of compiled files */
        .version([
            'public/css/app.css',
            'public/css/vendor.css',
            'public/js/vendor.js',
            'public/js/app.js'
        ])
        /* Copy font files */
        .copy(
            bowerDir + '/bootstrap/fonts',
            'public/build/fonts'
        )
        .copy(
            bowerDir + '/font-awesome/fonts',
            'public/build/fonts'
        );
});

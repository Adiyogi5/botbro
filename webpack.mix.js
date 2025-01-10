const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

mix.scripts([
    'node_modules/admin-lte/plugins/jquery/jquery.min.js',
    'node_modules/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js',
    'node_modules/admin-lte/plugins/select2/js/select2.min.js',
    'node_modules/admin-lte/plugins/toastr/toastr.min.js',
    'node_modules/admin-lte/plugins/sweetalert2/sweetalert2.min.js',
    'node_modules/admin-lte/plugins/jquery-validation/jquery.validate.min.js',
    'node_modules/admin-lte/plugins/jquery-validation/additional-methods.min.js',
    'node_modules/admin-lte/plugins/datatables/jquery.dataTables.min.js',
    'node_modules/admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js',
    'node_modules/admin-lte/plugins/datatables-responsive/js/dataTables.responsive.min.js',
    'node_modules/admin-lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js',

], 'public/js/all.min.js');

mix.scripts([ 
    'node_modules/admin-lte/plugins/select2/css/select2.min.css',
    'node_modules/admin-lte/plugins/toastr/toastr.min.css',
    'node_modules/admin-lte/plugins/sweetalert2/sweetalert2.min.css',

    ], 'public/css/all.min.css');
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
    mix.less([
        'site.less',
        'front/*.less',
    ], 'public/front.css');

    mix.styles([
        'site.less',
        'admin.less'
    ], 'public/admin.css');

    mix.coffee([
        'site.coffee',
        'front.coffee',
    ], 'public/front.js');

    mix.coffee([
        'site.coffee',
        'app.coffee',
        'controllers/*.coffee',
    ], 'public/admin.js');
});

<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

use App\Foundation\Application;
use App\Providers\AppServiceProvider;
use Silex\Provider\TwigServiceProvider;

$app   = new Application();
$debug = config('app.debug');

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new TwigServiceProvider(), [
    'twig.path'    => VIEW_PATH,
    'twig.options' => [
        'debug'            => $debug,
        'cache'            => $debug ? false : STORAGE_PATH.'/framework/views',
        'optimizations'    => $debug ? 0 : -1,

        /*
         * If set to false, Twig will silently ignore invalid variables
         * (variables and or attributes/methods that do not exist) and
         * replace them with a null value. When set to true,
         * Twig throws an exception instead (default to false).
         */
        'strict_variables' => true,
    ],
]);
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new AppServiceProvider());
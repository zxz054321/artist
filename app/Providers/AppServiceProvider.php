<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Providers;

use Silex\Application;
use Silex\ServiceProviderInterface;

class AppServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app->view(function (array $controllerResult) use ($app) {
            return $app->json($controllerResult);
        });

        $app['twig'] = $app->share($app->extend('twig', function ($twig) {
            $twig->addFunction(new \Twig_SimpleFunction('asset', function ($path) {
                return asset($path);
            }));

            return $twig;
        }));
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}
<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Providers;

use App\Repositories\Content\Content;
use App\Repositories\Content\Drivers\File;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Silex\Application;
use Silex\ServiceProviderInterface;

class AppServiceProvider implements ServiceProviderInterface
{

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

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['disk'] = $app->share(function () {
            $adapter    = new Local(STORAGE_PATH.'/app');
            $filesystem = new Filesystem($adapter);

            return $filesystem;
        });

        $this->setupView($app);

        $this->registerContent($app);
    }

    protected function setupView(Application $app)
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

    protected function registerContent(Application $app)
    {
        $app['content.revealed'] = $app->share(function () {
            return new Content(new File('contents/revealed'));
        });

        $app['content.unrevealed'] = $app->share(function () {
            return new Content(new File('contents/unrevealed'));
        });
    }
}
<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Providers;

use App\Repositories\Content\Content;
use App\Repositories\Content\Drivers\File;
use Silex\Application;
use Silex\ServiceProviderInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

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
        $app['disk'] = $app->share(function () {
            $adapter    = new Local(STORAGE_PATH.'/app');
            $filesystem = new Filesystem($adapter);

            return $filesystem;
        });

        $app['content.revealed'] = $app->share(function () {
            return new Content(new File('contents/revealed'));
        });

        $app['content.unrevealed'] = $app->share(function () {
            return new Content(new File('contents/unrevealed'));
        });
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
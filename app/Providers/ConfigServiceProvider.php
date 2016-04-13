<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Providers;

use Illuminate\Config\Repository;
use Silex\Application;
use Silex\ServiceProviderInterface;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $config = new Repository();

        $this->loadConfigurationFiles($config);

        $app['debug']  = $config->get('app.debug', false);
        $app['config'] = $config;
    }

    /**
     * Load the configuration items from all of the files.
     *
     * @param  \Illuminate\Contracts\Config\Repository $repository
     * @return void
     */
    protected function loadConfigurationFiles(\Illuminate\Contracts\Config\Repository $repository)
    {
        foreach ($this->getConfigurationFiles() as $key => $path) {
            $repository->set($key, require $path);
        }
    }

    /**
     * Get all of the configuration files for the application.
     *
     * @return array
     */
    protected function getConfigurationFiles()
    {
        $files = [];

        $configPath = realpath(CONFIG_PATH);

        foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
            $nesting = $this->getConfigurationNesting($file, $configPath);

            $files[ $nesting.basename($file->getRealPath(), '.php') ] = $file->getRealPath();
        }

        return $files;
    }

    /**
     * Get the configuration file nesting path.
     *
     * @param  \Symfony\Component\Finder\SplFileInfo $file
     * @param  string $configPath
     * @return string
     */
    protected function getConfigurationNesting(SplFileInfo $file, $configPath)
    {
        $directory = dirname($file->getRealPath());

        if ($tree = trim(str_replace($configPath, '', $directory), DIRECTORY_SEPARATOR)) {
            $tree = str_replace(DIRECTORY_SEPARATOR, '.', $tree).'.';
        }

        return $tree;
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
    }
}
<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Foundation;

use App\Providers\ConfigServiceProvider;

class Application extends \Silex\Application
{
    /**
     * The Glass framework version.
     *
     * @var string
     */
    const VERSION = '0.1.0';

    protected static $instance;

    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $this->registerBaseServiceProviders();

        self::$instance = $this;
    }

    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders()
    {
        $this->register(new ConfigServiceProvider());
    }

    public static function getInstance()
    {
        return self::$instance;
    }
}
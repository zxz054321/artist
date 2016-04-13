<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

use Silex\WebTestCase;

abstract class TestCase extends WebTestCase
{
    public function createApplication()
    {
        require ROOT. '/bootstrap/app.php';
        require ROOT.'/app/Http/routes.php';

        $app['debug']        = true;
        $app['session.test'] = true;

        unset($app['exception_handler']);

        return $app;
    }
}
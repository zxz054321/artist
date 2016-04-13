<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

use App\Foundation\Application;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Get the available container instance.
 *
 * @param  string $make
 * @return Application|mixed
 */
function app($make = null)
{
    $app = Application::getInstance();

    if (is_null($make)) {
        return $app;
    }

    return $app[ $make ];
}

/**
 * Get / set the specified configuration value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param  array|string $key
 * @param  mixed $default
 * @return mixed
 */
function config($key = null, $default = null)
{
    if (is_null($key)) {
        return app('config');
    }

    if (is_array($key)) {
        return app('config')->set($key);
    }

    return app('config')->get($key, $default);
}

/**
 * Throw an Http Exception with the given data.
 *
 * @param  int $code
 * @param  string $message
 * @return void
 */
function abort($code, $message = '')
{
    app()->abort($code, $message);
}

/**
 * Get the evaluated view contents for the given view.
 *
 * @param  string $name
 * @param  array $data
 * @return Twig_Environment
 */
function view($name = null, array $data = [])
{
    /** @var Twig_Environment $twig */
    $twig = app('twig');

    if (func_num_args() === 0) {
        return $twig;
    }

    return $twig->render($name.'.twig', $data);
}

/**
 * Generates an absolute URL from the given parameters.
 *
 * @param string $route The name of the route
 * @param mixed $parameters An array of parameters
 * @param $type
 *
 * @return string The generated URL
 */
function route($route, $parameters = [], $type = UrlGeneratorInterface::ABSOLUTE_URL)
{
    return app('url_generator')->generate($route, $parameters, $type);
}

/**
 * Get / set the specified session value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param  array|string $key
 * @param  mixed $default
 * @return Session|mixed|null
 */
function session($key = null, $default = null)
{
    /** @var Session $session */
    $session = app('session');

    if (is_null($key)) {
        return $session;
    }

    if (is_array($key)) {
        $session->set(key($key), current($key));

        /** @noinspection PhpInconsistentReturnPointsInspection */
        return;
    }

    return $session->get($key, $default);
}
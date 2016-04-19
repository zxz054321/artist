<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

use App\Foundation\Application;
use App\Services\SimpleAuth;
use Symfony\Component\HttpFoundation\Request;

return function (Request $request, Application $app) {
    $auth = new SimpleAuth();

    if ($request->getPathInfo() == '/admin/login') {
        if (!$auth->guest()) {
            return $app->redirect(route('dashboard'));
        }

        /** @noinspection PhpInconsistentReturnPointsInspection */
        return;
    }

    if (!app('session.test') && $auth->guest()) {
        return $app->redirect(route('admin', ['route' => 'login']));
    }
};
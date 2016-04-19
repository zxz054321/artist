<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Admin\TagController;
use Silex\ControllerCollection;

if (!defined('MIDDLEWARE')) {
    define('MIDDLEWARE', APP_PATH.'/Http/Middleware/');
}

$app->get('/', FrontController::class.'::index');
$app->get('article/{route}.html', FrontController::class.'::show')->bind('article');

/*
 * Auth
 */
$app->post('auth/login', AuthController::class.'::login');
$app->get('auth/logout', AuthController::class.'::logout')->bind('logout');

/*
 * Admin
 */
/** @var ControllerCollection $admin */
$admin = $app['controllers_factory']
    ->before(require MIDDLEWARE.'Authenticate.php');

$admin->get('/', AdminController::class.'::dashboard');

$admin->get('content', ContentController::class.'::all');
$admin->get('content/check', ContentController::class.'::check');
$admin->post('content/store', ContentController::class.'::store');
$admin->get('content/{id}', ContentController::class.'::raw');
$admin->put('content/{id}', ContentController::class.'::update');
$admin->delete('content/{id}', ContentController::class.'::delete');

$admin->get('{route}.ng', AdminController::class.'::ngView');
$admin->get('{route}', AdminController::class.'::view')->bind('admin');

$app->mount('admin', $admin);
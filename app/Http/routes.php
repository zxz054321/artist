<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Admin\TagController;

if (!defined('MIDDLEWARE')) {
    define('MIDDLEWARE', APP_PATH.'/Http/Middleware/');
}

$app->get('/', FrontController::class.'::index');
$app->get('article/{id}.html', FrontController::class.'::show')->bind('article');

/*
 * Auth
 */
$app->post('auth/login', AuthController::class.'::login');
$app->get('auth/logout', AuthController::class.'::logout')->bind('logout');

/*
 * Admin
 */
$admin = $app['controllers_factory']
    ->before(require MIDDLEWARE.'Authenticate.php');

$admin->get('/', AdminController::class.'::login');
$admin->get('login', AdminController::class.'::login')->bind('login');
$admin->get('dashboard', AdminController::class.'::dashboard')->bind('dashboard');
$admin->get('write', AdminController::class.'::write')->bind('write');
$admin->get('contents', AdminController::class.'::contents')->bind('contents');
$admin->get('tags', AdminController::class.'::tags')->bind('tags');
$admin->get('navbar', AdminController::class.'::navbar')->bind('navbar');

$admin->get('content', ContentController::class.'::all');
$admin->get('content/check', ContentController::class.'::check');
$admin->post('content/store', ContentController::class.'::store');
$admin->get('content/{id}', ContentController::class.'::raw');
$admin->put('content/{id}', ContentController::class.'::update');
$admin->delete('content/{id}', ContentController::class.'::delete');

$admin->get('tag', TagController::class.'::index');

$app->mount('admin', $admin);
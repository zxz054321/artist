<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\TagController;

if (!defined('MIDDLEWARE')) {
    define('MIDDLEWARE', APP_PATH.'/Http/Middleware/');
}

$app->get('/', AppController::class.'::welcome');
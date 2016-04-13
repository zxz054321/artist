<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

use App\Http\Controllers\AppController;

if (!defined('MIDDLEWARE')) {
    define('MIDDLEWARE', APP_PATH.'/Http/Middleware/');
}

$app->get('/', AppController::class.'::welcome');
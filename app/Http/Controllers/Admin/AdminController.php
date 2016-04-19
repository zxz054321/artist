<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Http\Controllers\Admin;

use Parsedown;
use Silex\Application\TwigTrait;

class AdminController
{
    public function view($route)
    {
        return view('admin/'.$route);
    }
}
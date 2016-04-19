<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Http\Controllers\Admin;

use Parsedown;
use Silex\Application\TwigTrait;

class AdminController
{
    public function ngView($route)
    {
        $tpl = VIEW_PATH.'/admin/ng/'.$route.'.html';

        return file_get_contents($tpl);
    }

    public function view($route)
    {
        return view('admin/'.$route);
    }
}
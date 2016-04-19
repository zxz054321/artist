<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Http\Controllers\Admin;

class AdminController
{
    public function ngView($route)
    {
        $tpl = VIEW_PATH.'/admin/ng/'.$route.'.html';

        file_exists($tpl) or abort(404);

        return file_get_contents($tpl);
    }

    public function view($route)
    {
        $file = VIEW_PATH.'/admin/'.$route.'.twig';

        file_exists($file) or abort(404);

        return view('admin/'.$route);
    }
}
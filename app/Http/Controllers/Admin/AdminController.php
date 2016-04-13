<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Http\Controllers\Admin;

use Parsedown;
use Silex\Application\TwigTrait;

class AdminController
{
    public function login()
    {
        return view('admin/login');
    }

    public function dashboard()
    {
        return view('admin/dashboard');
    }

    public function write()
    {
        return view('admin/write');
    }

    public function contents()
    {
        return view('admin/contents');
    }

    public function tags()
    {
        return view('admin/tags');
    }

    public function navbar()
    {
        return view('admin/navbar');
    }
}
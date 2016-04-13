<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Http\Controllers;

use App\Foundation\Application;

class AppController
{
    public function welcome()
    {
        return view('welcome', [
            'version' => Application::VERSION,
            'motto'   => 'If I have seen further, it is by standing on the shoulders of giants.',
            'author'  => 'Isaac Newton',
        ]);
    }
}
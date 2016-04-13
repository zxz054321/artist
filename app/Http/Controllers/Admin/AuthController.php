<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Services\SimpleAuth;
use Symfony\Component\HttpFoundation\Request;

class AuthController
{
    protected $auth;

    public function __construct()
    {
        $this->auth = new SimpleAuth();
    }

    public function login(Request $request)
    {
        $password = json_decode($request->getContent())->password;

        $pass = $this->auth->attempt($password);

        return app()->json(['match' => $pass]);
    }

    public function logout()
    {
        $this->auth->logout();

        return app()->redirect(route('login'));
    }
}
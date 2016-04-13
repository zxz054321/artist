<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Services;


class SimpleAuth
{
    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array $credentials
     * @param  bool $remember
     * @param  bool $login
     * @return bool
     */
    public function attempt($password, $remember = false, $login = true)
    {
        if (!$this->verify($password)) {
            return false;
        }

        //TODO $remember

        if ($login) {
            session(['authenticated' => time()]);
        }

        return true;
    }

    public function verify($password)
    {
        return $password == config('admin.password');
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return !session('authenticated');
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        session(['authenticated' => false]);
    }
}
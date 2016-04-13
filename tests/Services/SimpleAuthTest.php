<?php
use App\Foundation\Application;
use App\Services\SimpleAuth;
use Illuminate\Config\Repository;

/**
 * Author: Abel Halo <zxz054321@163.com>
 */
class SimpleAuthTest extends TestCase
{
    /**
     * @var SimpleAuth
     */
    protected $auth;

    public function setUp()
    {
        parent::setUp();

        $this->auth = new SimpleAuth;
    }

    public function testVerify()
    {
        config(['admin.password' => '054321']);

        $this->assertEquals(false, $this->auth->verify('123456'));
        $this->assertEquals(true, $this->auth->verify('054321'));
    }

    public function testAttempt()
    {
        config(['admin.password' => '123456']);

        $this->assertEquals(false, $this->auth->attempt('654321'));
        $this->assertEquals(true, $this->auth->attempt('123456'));
    }

    public function testGuest()
    {
        config(['admin.password' => '123456']);

        $this->assertEquals(true, $this->auth->guest());

        $this->auth->attempt('123456');

        $this->assertEquals(false, $this->auth->guest());
    }

    public function testLogout()
    {
        config(['admin.password' => '123456']);

        $this->auth->attempt('123456');
        $this->assertEquals(false, $this->auth->guest());

        $this->auth->logout();
        $this->assertEquals(true, $this->auth->guest());
    }
}

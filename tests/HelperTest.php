<?php
use App\Foundation\Application;
use Illuminate\Config\Repository;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Author: Abel Halo <zxz054321@163.com>
 */
class HelperTest extends TestCase
{
    public function testApp()
    {
        $this->assertInstanceOf(Application::class, app());
    }

    public function testConfig()
    {
        $salt = str_random();

        config(['app.test' => $salt]);

        $this->assertInstanceOf(Repository::class, config());

        $this->assertEquals($salt, config('app.test'));
        $this->assertEquals(true, is_array(config('app')));
        $this->assertEquals(true, is_bool(config('app.debug')));
    }

    public function testSession()
    {
        $key = 'test_'.md5(get_class($this));
        $val = str_random();

        session([$key => $val]);

        $this->assertInstanceOf(Session::class, session());
        $this->assertEquals($val,session($key));
    }
}

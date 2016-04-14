<?php

namespace Tag\Drivers;

use App\Repositories\Tag\Drivers\File;
use App\Repositories\Tag\Tag;
use App\Repositories\Tag\TagNotFoundException;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;

/**
 * Author: Abel Halo <zxz054321@163.com>
 */
class FileTest extends \TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->app['disk'] = $this->app->share(function () {
            $filesystem = new Filesystem(new MemoryAdapter);

            return $filesystem;
        });
    }

    public function testMake()
    {
        $driver = new File();

        $this->assertEquals(new Tag(), $driver->make('a'));

        $driver->make('b')
            ->referTo('a1')
            ->referTo('a2');

        $this->assertEquals(2, $driver->collection()->count());
        $this->assertEquals(2, $driver->make('b')->count());

        $this->assertInstanceOf(Tag::class, $driver->make('b'));

        return $driver;
    }

    /**
     * @depends testMake
     */
    public function testExists(File $driver)
    {
        $this->assertEquals(true, $driver->exists('a'));
        $this->assertEquals(true, $driver->exists('b'));
        $this->assertEquals(false, $driver->exists('ccc'));
    }

    /**
     * @depends testMake
     */
    public function testTag(File $driver)
    {
        $this->assertInstanceOf(Tag::class, $driver->tag('a'));
        $this->assertInstanceOf(Tag::class, $driver->tag('b'));

        try {
            $exception = false;

            $driver->tag('000');
        } catch (\Exception $e) {
            $exception = true;

            $this->assertInstanceOf(TagNotFoundException::class, $e);
        }

        $this->assertEquals(true, $exception);
        $this->assertEquals(2, $driver->collection()->count());
    }

    /**
     * @depends testMake
     */
    public function testDestory(File $driver)
    {
        $driver->destory('a');
        $this->assertEquals(1, $driver->collection()->count());

        $driver->destory('b');
        $this->assertEquals(0, $driver->collection()->count());

        try {
            $exception = false;
            $driver->destory('000');
        } catch (\Exception $e) {
            $exception = true;
            $this->assertInstanceOf(TagNotFoundException::class, $e);
        }

        $this->assertEquals(true, $exception);
        $this->assertEquals(0, $driver->collection()->count());
    }

    public function testSave()
    {
        $driver = new File();

        $driver->make('a')
            ->referTo('a1')
            ->referTo('a2');

        $this->assertEquals(true,$driver->save());

        /*
         * check
         */
        $driver = new File();

        $this->assertEquals(2, $driver->tag('a')->count());
    }
}
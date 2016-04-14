<?php

namespace Services;

use App\Repositories\Tag\Manager;
use App\Services\Tag;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;

/**
 * Author: Abel Halo <zxz054321@163.com>
 */
class TagTest extends \TestCase
{
    /**
     * @var Tag
     */
    protected $service;

    public function setUp()
    {
        parent::setUp();

        $this->app['disk'] = $this->app->share(function () {
            $filesystem = new Filesystem(new MemoryAdapter);

            return $filesystem;
        });

        $this->service = new Tag();
    }

    public function testMake()
    {
        $id = str_random();

        $this->service->make($id, ['a', 'b'])->save();

        $this->service = new Tag();

        $manager = $this->service->manager();
        $this->assertEquals(2, $manager->collection()->count());

        $this->assertEquals(true, $manager->tag('a')->contains($id));
        $this->assertEquals(true, $manager->tag('b')->contains($id));
    }

    public function testUnreference()
    {
        $id = str_random();

        $this->service
            ->make($id, ['a', 'b', 'c'])
            ->unreference($id, ['a', 'c'])
            ->save();

        $manager = (new Tag())->manager();

        $this->assertEquals(1, $manager->collection()->count());

        $this->assertEquals(false, $manager->exists('a'));
        $this->assertEquals(true, $manager->tag('b')->contains($id));
        $this->assertEquals(false, $manager->exists('c'));
    }

    public function testSync()
    {
        $id = str_random();

        $this->service
            ->make($id, ['a', 'b'])
            ->sync($id, ['b', 'c']);

        $manager = $this->service->manager();

        $this->assertEquals(2, $manager->collection()->count());

        $this->assertEquals(false, $manager->exists('a'));
        $this->assertEquals(true, $manager->tag('b')->contains($id));
        $this->assertEquals(true, $manager->tag('c')->contains($id));
    }
}
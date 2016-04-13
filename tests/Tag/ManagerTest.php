<?php

use App\Repositories\Tag\Manager;
use App\Repositories\Tag\Tag;
use App\Repositories\Tag\TagNotFoundException;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;

/**
 * Author: Abel Halo <zxz054321@163.com>
 */
class ManagerTest extends TestCase
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
        $manager = new Manager();

        $this->assertInstanceOf(Tag::class,
            $manager->make('aaa')
                ->refer('a1')
                ->refer('a2')
        );

        $this->assertEquals(2,
            $manager->make('aaa')->count()
        );

        $this->assertInstanceOf(Tag::class, $manager->make('bbb'));

        return $manager;
    }

    /**
     * @depends testMake
     */
    public function testCount(Manager $manager)
    {
        $this->assertEquals(2, $manager->count());
    }

    /**
     * @depends testMake
     */
    public function testExists(Manager $manager)
    {
        $this->assertEquals(true, $manager->exists('aaa'));
        $this->assertEquals(true, $manager->exists('bbb'));
        $this->assertEquals(false, $manager->exists('ccc'));
    }

    /**
     * @depends testMake
     */
    public function testTag(Manager $manager)
    {
        $this->assertInstanceOf(Tag::class, $manager->tag('aaa'));
        $this->assertInstanceOf(Tag::class, $manager->tag('bbb'));

        try {
            $manager->tag('000');
        } catch (\Exception $e) {
            $this->assertInstanceOf(TagNotFoundException::class, $e);
        }

        $this->assertEquals(2, $manager->count());
    }

    /**
     * @depends testMake
     */
    public function testDestory(Manager $manager)
    {
        $manager->destory('aaa');
        $this->assertEquals(1, $manager->count());

        $manager->destory('bbb');
        $this->assertEquals(0, $manager->count());

        try {
            $manager->destory('000');
        } catch (\Exception $e) {
            $this->assertInstanceOf(TagNotFoundException::class, $e);
        }

        $this->assertEquals(0, $manager->count());
    }

    public function testClean()
    {
        $manager = new Manager();

        $manager->make('aaa')
            ->refer('a1')
            ->refer('a2');

        $manager->make('bbb');

        $manager->clean();

        $this->assertEquals(1, $manager->count());
    }

    public function testToArray()
    {
        $manager = $this->generateTestManager();
        $arr     = $manager->toArray();

        $this->assertArrayHasKey('aaa', $arr);
        $this->assertEquals(2, count($arr['aaa']));
    }

    protected function generateTestManager()
    {
        $manager = new Manager();

        $manager->make('aaa')
            ->refer('a1')
            ->refer('a2');

        return $manager;
    }

    public function testSave()
    {
        $manager = $this->generateTestManager();

        $manager->save();

        /** @var Filesystem $disk */
        $disk = app('disk');

        $this->assertEquals($disk->read('tags'),
            serialize([
                'aaa' => [
                    'a1',
                    'a2',
                ],
            ])
        );

        $manager = new Manager();

        $this->assertEquals(2, $manager->make('aaa')->count());
    }
}

<?php

use App\Repositories\Content\Content;
use App\Repositories\Content\Drivers\File;
use App\Repositories\Collection\Exceptions\DataSpoiledException;
use Illuminate\Support\Collection;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use Ramsey\Uuid\Uuid;

/**
 * Author: Abel Halo <zxz054321@163.com>
 */
class FileTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->app['disk'] = $this->app->share(function () {
            $filesystem = new Filesystem(new MemoryAdapter);

            return $filesystem;
        });
    }

    public function testLoad()
    {
        $driver = new File('contents/test');

        /** @var Filesystem $disk */
        $disk = app('disk');

        $disk->put('index', serialize([
            'test1' => [
                'title'      => 'Test1',
                'summary'    => 'summarysummary',
                'tags'       => [
                    'aa',
                    'bb',
                ],
                'created_at' => time(),
                'status'     => Content::STATUS_DRAFT,
            ],
        ]));

        try {
            $driver->load();
        } catch (\Exception $e) {
            $this->assertInstanceOf(DataSpoiledException::class, $e);
        }
    }

    public function testCollection()
    {
        $driver = new File('contents/test');

        $this->assertInstanceOf(Collection::class, $driver->collection());
    }

    public function testCreate()
    {
        $driver = new File('contents/test');
        $data   = [
            'title'      => 'How are you?',
            'route'      => '-test-'.str_random(),
            'summary'    => str_random(64),
            'content'    => str_random(128),
            'tags'       => ['aa', 'bb'],
            'created_at' => time(),
            'status'     => Content::STATUS_PUBLISHED,
        ];

        $id = $driver->create($data);

        $this->assertEquals(true, Uuid::isValid($id));
        $this->assertEquals(1, $driver->collection()->count());

        $data = [
            'title'      => 'Hi there',
            'route'      => '-test-'.str_random(),
            'summary'    => str_random(64),
            'content'    => str_random(128),
            'tags'       => ['cc',],
            'created_at' => time(),
            'status'     => Content::STATUS_DRAFT,
        ];

        $driver->create($data);

        $this->assertEquals(2, $driver->collection()->count());
        $this->assertEquals('Hi there',
            $driver->collection()->first()['title']
        );

        return $driver;
    }

    /**
     * @depends testCreate
     */
    public function testUpdate(File $driver)
    {
        $arr = $driver->collection()->toArray();
        $id  = key($arr);

        $driver->update($id, [
            'title' => 'Fine, thank you',
            'tags'  => ['bb', 'cc'],
        ]);

        $item = $driver->collection()->get($id);

        $this->assertEquals('Fine, thank you', $item['title']);
        $this->assertEquals(['bb', 'cc'], $item['tags']);
        $this->assertEquals('draft', $item['status']);
    }

    /**
     * @depends testCreate
     */
    public function testDelete(File $driver)
    {
        $arr = $driver->collection()->toArray();
        $id  = key($arr);

        $this->assertEquals(true, $driver->delete($id));
        $this->assertEquals(1, $driver->collection()->count());
    }

    /**
     * @depends testCreate
     */
    public function testRead(File $driver)
    {
        $arr  = $driver->collection()->toArray();
        $id   = key($arr);
        $data = $driver->read($id);

        $this->assertEquals('How are you?', $data['title']);
        $this->assertEquals(64, strlen($data['summary']));
        $this->assertEquals(128, strlen($data['content']));
        $this->assertEquals(['aa', 'bb'], $data['tags']);
        $this->assertEquals(Content::STATUS_PUBLISHED, $data['status']);
    }

    /**
     * @depends testCreate
     */
    public function testExists(File $driver)
    {
        $arr = $driver->collection()->toArray();
        $id  = key($arr);

        $this->assertEquals(true, $driver->exists($id));
        $this->assertEquals(false, $driver->exists('abc'));

        $this->assertEquals(true, $driver->exists([
            'title' => 'How are you?',
        ]));
        $this->assertEquals(false, $driver->exists([
            'title' => 'hhhh',
        ]));
    }

    /**
     * @depends testCreate
     */
    public function testSave(File $driver)
    {
//        dd( $driver->save());
        $this->assertEquals(true, $driver->save());
//
//        $driver2 = new File();
//
//        $this->assertEquals(1, $driver2->collection()->count());
    }
}
<?php

use App\Repositories\Content\Content;
use App\Repositories\Content\Drivers\File;
use App\Repositories\Content\Exceptions\NotFoundException;
use App\Repositories\Content\Exceptions\RouteExistsException;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use Ramsey\Uuid\Uuid;

/**
 * Author: Abel Halo <zxz054321@163.com>
 */
class ContentTest extends TestCase
{
    /**
     * @var File
     */
    protected $driver;

    public function setUp()
    {
        parent::setUp();

        $this->app['disk'] = $this->app->share(function () {
            $filesystem = new Filesystem(new MemoryAdapter);

            return $filesystem;
        });

        $this->driver = new File('contents/test');
    }

    public function testCreate()
    {
        $content = new Content($this->driver);
        $data    = [
            'title'      => 'How are you?',
            'route'      => '-test-'.str_random(),
            'summary'    => str_random(64),
            'content'    => str_random(128),
            'tags'       => ['aa', 'bb'],
            'created_at' => time(),
            'status'     => Content::STATUS_PUBLISHED,
        ];

        $id = $content->create($data);

        $this->assertEquals(true, Uuid::isValid($id));
        $this->assertEquals(1, $content->collection()->count());

        try {
            $exception = false;
            $content->create($data);
        } catch (Exception $e) {
            $exception = true;
            $this->assertInstanceOf(RouteExistsException::class, $e);
        }

        $this->assertEquals(true, $exception);

        return $content;
    }

    /**
     * @depends testCreate
     */
    public function testUpdate(Content $content)
    {
        $arr = $content->collection()->toArray();
        $id  = key($arr);

        $content->update($id, [
            'title' => 'Fine, thank you',
            'tags'  => ['bb', 'cc'],
        ]);

        $item = $content->collection()->get($id);

        $this->assertEquals('Fine, thank you', $item['title']);
        $this->assertEquals(['bb', 'cc'], $item['tags']);
        $this->assertEquals(Content::STATUS_PUBLISHED, $item['status']);

        try {
            $exception = false;
            $content->update('hhhhh', [
                'title' => 'What\'s up',
            ]);
        } catch (Exception $e) {
            $exception = true;
            $this->assertInstanceOf(NotFoundException::class, $e);
        }

        $this->assertEquals(true, $exception);
    }
}

<?php

namespace Services;

use App\Repositories\Content\Content as ContentRepository;
use App\Repositories\Content\Exceptions\NotFoundException;
use App\Repositories\Content\Exceptions\RouteExistsException;
use App\Services\Content;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use Ramsey\Uuid\Uuid;

/**
 * Author: Abel Halo <zxz054321@163.com>
 */
class ContentTest extends \TestCase
{
    /**
     * @var Content
     */
    protected $content;

    public function setUp()
    {
        parent::setUp();

        $this->app['disk'] = $this->app->share(function () {
            $filesystem = new Filesystem(new MemoryAdapter);

            return $filesystem;
        });

        $this->content = new Content();
    }

    public function testCreate()
    {
        /*
         * 1
         */
        $data = $this->data();

        $id = $this->content->create($data);

        $this->assertEquals(true, Uuid::isValid($id));
        $this->assertEquals(Content::EXISTS_AS_REVEALED,
            $this->content->exists($id)
        );

        /*
         * 2
         */
        $data['route']  = '-test-'.str_random();
        $data['status'] = ContentRepository::STATUS_DRAFT;

        $id = $this->content->create($data);

        $this->assertEquals(true, Uuid::isValid($id));
        $this->assertEquals(Content::EXISTS_AS_UNREVEALED,
            $this->content->exists($id)
        );

        /*
         * repeat 2
         */
        try {
            $exception = false;

            $this->content->create($data);
        } catch (\Exception $e) {
            $exception = true;
            $this->assertInstanceOf(RouteExistsException::class, $e);
        }

        $this->assertEquals(true, $exception);
    }

    protected function data()
    {
        return [
            'title'      => 'test1',
            'route'      => '-test-'.str_random(),
            'summary'    => str_random(64),
            'content'    => str_random(128),
            'tags'       => 'aa,bb,c',
            'cover'      => 'http://guo.lu/wp-content/themes/Diaspora/timthumb/timthumb.php?src=http://guo.lu/wp-content/uploads/2016/03/wallhaven-215913.jpg',
            'created_at' => time(),
            'status'     => ContentRepository::STATUS_PUBLISHED,
        ];
    }

    public function testGet()
    {
        $data = $this->data();

        $id = $this->content->create($data);

        $this->assertEquals($data, $this->content->get($id));
    }

    public function testUpdate()
    {
        /*
         * create a draft
         */
        $old           = $this->data();
        $old['status'] = ContentRepository::STATUS_DRAFT;
        $id            = $this->content->create($old);

        /*
         * update it to published
         */
        $new = [
            'title'      => 'hhhh',
            'route'      => '-test-'.str_random(),
            'content'    => str_random(128),
            'tags'       => 'c,dd',
            'updated_at' => time() + 100,
            'status'     => ContentRepository::STATUS_PUBLISHED,
        ];

        $this->assertEquals(true, $this->content->update($id, $new));
        $this->assertEquals(Content::EXISTS_AS_REVEALED,
            $this->content->exists($id)
        );

        /*
         * did it work?
         */
        $data = $this->content->get($id);

        $new['created_at'] = $new['updated_at'];
        $new               = array_merge($old, $new);

        unset($new['updated_at']);

        $this->assertEquals(true, $old['created_at'] < $data['created_at']);
        $this->assertEquals(false, isset($data['updated_at']));
        $this->assertEquals($new, $data);

        /*
         * create a new draft from it
         */
        $data['content']    = str_random(128);
        $data['updated_at'] = time() + 200;
        $data['status']     = ContentRepository::STATUS_DRAFT;

        $this->assertEquals(true, $this->content->update($id, $data));

        $this->assertEquals(true,
            $this->content->getUnrevealed()->exists($id),
            'a copy of draft should be in the Unrevealed list'
        );

        $this->assertEquals(Content::EXISTS_AS_REVEALED,
            $this->content->exists($id),
            'the original copy should still be there'
        );

        /*
         * did it work?
         */
        $draft = $this->content->get($id);

        $this->assertEquals($data, $draft,
            'The original copy should not be affected.'
        );

        /*
         * publish the draft copy
         */
        $publication = array_merge($draft, [
            'content'    => str_random(128),
            'updated_at' => time() + 300,
            'status'     => ContentRepository::STATUS_PUBLISHED,
        ]);

        $this->assertEquals(true, $this->content->update($id, $publication));

        $this->assertEquals(false,
            $this->content->getUnrevealed()->exists($id),
            'No copy of draft should be in the Unrevealed list now.'
        );

        $this->assertEquals(Content::EXISTS_AS_REVEALED,
            $this->content->exists($id),
            'The original copy should still be there.'
        );

        /*
         * try sth not exists
         */
        try {
            $exception = false;

            $this->content->update('000000', $new);
        } catch (\Exception $e) {
            $exception = true;
            $this->assertInstanceOf(NotFoundException::class, $e);
        }

        $this->assertEquals(true, $exception);
    }
}

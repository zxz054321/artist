<?php

use App\Repositories\Content\Content;

use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Client;

/**
 * Author: Abel Halo <zxz054321@163.com>
 */
class ContentControllerTest extends TestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->app['disk'] = $this->app->share(function () {
            $filesystem = new Filesystem(new MemoryAdapter);

            return $filesystem;
        });

        $this->client = $this->createClient();
    }

    public function testStore()
    {
        /*
         * 1
         */
        $rawArr = $this->doTestRequest('POST', '/admin/content/store');
        $res    = $this->decode();

        $this->assertEquals(true, $res->result);
        $this->assertEquals(true, Uuid::isValid($res->id));

        /*
         * repeat 1
         */
        $this->doTestRequest('POST', '/admin/content/store', $rawArr);

        $res = $this->decode();

        $this->assertEquals(false, $res->result);
        $this->assertEquals('route.exists', $res->err);
    }

    protected function doTestRequest($method, $route, $raw = null)
    {
        if (is_null($raw)) {
            $raw = [
                'title'      => 'test1',
                'route'      => '-test-'.str_random(),
                'summary'    => str_random(64),
                'content'    => str_random(128),
                'tags'       => 'aa,bb,c',
                'created_at' => time(),
                'status'     => Content::STATUS_PUBLISHED,
            ];
        }

        $this->client->request($method,
            $route,
            [], [], [],
            json_encode($raw)
        );

        return $raw;
    }

    protected function decode()
    {
        $res = $this->client->getResponse()->getContent();

        return json_decode($res);
    }

    public function testRaw()
    {
        /*
         * create one
         */
        $original = $this->doTestRequest('POST', '/admin/content/store');

        /*
         * get and check it
         */
        $this->client->request('GET', '/admin/content/'.$this->decode()->id);

        $original['tags'] = explode(',', $original['tags']);

        $this->assertEquals($original, (array)$this->decode()->raw);
    }

    public function testUpdate()
    {
        /*
         * set first data
         */
        $old = $this->doTestRequest('POST', '/admin/content/store');
        $id  = $this->decode()->id;

        /*
         * update it
         */
        $new = [
            'title' => 'hhhh',
            'route' => '-test-'.str_random(),
            'tags'  => 'c,dd',
        ];

        $this->doTestRequest('PUT', "/admin/content/{$id}", $new);

        $this->assertEquals(true, $this->decode()->result);

        /*
         * check if any data miss
         */
        $this->client->request('GET', '/admin/content/'.$id);

        $raw = $this->decode()->raw;

        $old['title'] = $new['title'];
        $old['route'] = $new['route'];
        $old['tags']  = explode(',', $new['tags']);

        unset($raw->updated_at);

        $this->assertEquals($old, (array)$raw);
    }

    /**
     * @depends testStore
     */
    public function testAll()
    {
        $this->doTestRequest('POST', '/admin/content/store');

        $this->client->request('GET', '/admin/content');

        $data = $this->decode();

        $this->assertEquals(1, count($data->revealed));
        $this->assertEquals(0, count($data->unrevealed));
    }

    public function testCheck()
    {
        $arr   = $this->doTestRequest('POST', '/admin/content/store');
        $route = $arr['route'];

        $this->client->request('GET',
            "/admin/content/check?route={$route}"
        );

        $data = $this->decode();

        $this->assertEquals(true, $data->exists);

        $this->client->request('GET',
            '/admin/content/check?route=s5d4fd5'
        );

        $data = $this->decode();

        $this->assertEquals(false, $data->exists);
    }

    public function testDelete()
    {
        $this->doTestRequest('POST', '/admin/content/store');

        $id = $this->decode()->id;

        $this->client->request('DELETE', '/admin/content/'.$id);

        $result = $this->decode()->result;

        $this->assertEquals(true, $result);
    }
}

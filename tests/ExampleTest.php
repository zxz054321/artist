<?php
use App\Foundation\Application;
use Phalcon\Http\Client\Request;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;

/**
 * Author: Abel Halo <zxz054321@163.com>
 */
class ExampleTest extends TestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = $this->createClient();
    }

    public function testShouldSeeWelcome()
    {
        $this->client = $this->createClient();
        $this->client->request('GET', '/');
        $crawler = $this->client->getCrawler();

        $this->assertEquals('Glass', $crawler->filter('.title')->text());

        $this->assertEquals('Version: '.Application::VERSION,
            $crawler->filter('.content small')->text()
        );
    }
}

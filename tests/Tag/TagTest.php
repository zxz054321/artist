<?php

use App\Repositories\Tag\Tag;

/**
 * Author: Abel Halo <zxz054321@163.com>
 */
class TagTest extends TestCase
{
    public function testCount()
    {
        $tag = $this->generateTestTag();

        $this->assertEquals(2, $tag->count());

        return $tag;
    }

    protected function generateTestTag()
    {
        return new Tag([
            'aaa',
            'bbb',
        ]);
    }

    /**
     * @depends testCount
     */
    public function testExists(Tag $tag)
    {
        $this->assertEquals(true, $tag->exists('aaa'));
        $this->assertEquals(true, $tag->exists('bbb'));
        $this->assertEquals(false, $tag->exists('ccc'));
    }

    public function testRefer()
    {
        $tag = $this->generateTestTag();

        $tag->refer('000');
        $this->assertEquals(3, $tag->count());

        $tag->refer([1, '1111']);
        $this->assertEquals(5, $tag->count());
    }

    public function testUnreference()
    {
        $tag = $this->generateTestTag();

        $tag->unreference('000');
        $this->assertEquals(2, $tag->count());

        $tag->unreference('aaa');
        $this->assertEquals(1, $tag->count());

        $tag->unreference('bbb');
        $this->assertEquals(0, $tag->count());
    }
}

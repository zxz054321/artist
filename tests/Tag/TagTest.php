<?php

use App\Repositories\Tag\Tag;

/**
 * Author: Abel Halo <zxz054321@163.com>
 */
class TagTest extends TestCase
{
    public function testReferTo()
    {
        $tag = new Tag(['aaa', 'bbb',]);

        $tag->referTo('000');
        $this->assertEquals(3, $tag->count());

        $tag->referTo(['cc', '1111']);
        $this->assertEquals(5, $tag->count());

        $tag->referTo('000');
        $this->assertEquals(5, $tag->count());
    }

    public function testUnreference()
    {
        $tag = new Tag(['aaa', 'bbb',]);

        $tag->unreference('000');
        $this->assertEquals(2, $tag->count());

        $tag->unreference('aaa');
        $this->assertEquals(1, $tag->count());

        $tag->unreference('bbb');
        $this->assertEquals(0, $tag->count());
    }
}

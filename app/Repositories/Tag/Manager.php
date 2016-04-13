<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Repositories\Tag;

use League\Flysystem\Filesystem;

class Manager
{
    /**
     * @var Filesystem
     */
    protected $disk;
    protected $tags = [];

    public function __construct()
    {
        $this->disk = app('disk');

        if ($this->disk->has('tags')) {
            $tags = unserialize($this->disk->read('tags'));

            foreach ($tags as $tag => $val) {
                $this->tags[ $tag ] = new Tag($val);
            }
        }
    }

    /**
     * @param $tag
     * @return Tag
     */
    public function make($tag)
    {
        if (!isset($this->tags[ $tag ])) {
            $this->tags[ $tag ] = new Tag();
        }

        return $this->tags[ $tag ];
    }

    public function toArray()
    {
        $data = [];

        /** @var Tag $tag */
        foreach ($this->tags as $name => $tag) {
            $data[ $name ] = $tag->all();
        }

        return $data;
    }

    /**
     * @param $name
     * @return Tag
     * @throws TagNotFoundException
     */
    public function tag($name)
    {
        if (!$this->exists($name)) {
            throw new TagNotFoundException;
        }

        return $this->tags[ $name ];
    }

    public function exists($name)
    {
        return isset($this->tags[ $name ]);
    }

    public function destory($name)
    {
        if (!$this->exists($name)) {
            throw new TagNotFoundException;
        }

        unset($this->tags[ $name ]);
    }

    public function count()
    {
        return count($this->tags);
    }

    public function clean()
    {
        $gc = [];

        /** @var Tag $tag */
        foreach ($this->tags as $name => $tag) {
            if ($tag instanceof Tag && $tag->count()) {
                continue;
            }

            $gc[] = $name;
        }

        foreach ($gc as $name) {
            unset($this->tags[ $name ]);
        }
    }

    public function save()
    {
        $data = $this->toArray();

        return $this->disk->put('tags', serialize($data));
    }
}
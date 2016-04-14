<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Repositories\Tag\Drivers;

use App\Repositories\Collection\FileCollection;
use App\Repositories\Tag\DriverContract;
use App\Repositories\Tag\Tag;
use App\Repositories\Tag\TagNotFoundException;
use Illuminate\Support\Collection;

class File extends FileCollection implements DriverContract
{
    public function __construct()
    {
        parent::__construct();

        $this->file = 'tags';

        $this->load();
    }

    public function load()
    {
        $data = [];

        if ($this->disk->has($this->file)) {
            $data = unserialize($this->disk->read($this->file));

            if (!is_array($data)) {
                throw new DataSpoiledException;
            }

            foreach ($data as $key => &$item) {
                $item = new Tag($item);
            }
        }

        $this->collection = new Collection($data);
    }

    /**
     * @param Collection $collection
     * @return void
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;
    }

    /**
     * Create a tag if not exists
     * @param string $tag
     * @return Tag
     */
    public function make($tag)
    {
        if (!$this->collection->has($tag)) {
            $this->collection->put($tag, new Tag());
        }

        return $this->collection->get($tag);
    }

    /**
     * Get the tag
     * @param string $name
     * @return Tag
     * @throws TagNotFoundException
     */
    public function tag($name)
    {
        if (!$this->collection->has($name)) {
            throw new TagNotFoundException;
        }

        return $this->collection->get($name);
    }

    public function exists($tag)
    {
        return $this->collection->has($tag);
    }

    /**
     * @param string $tag
     * @return bool
     * @throws TagNotFoundException
     */
    public function destory($tag)
    {
        if (!$this->collection->has($tag)) {
            throw new TagNotFoundException;
        }

        $this->collection->forget($tag);
    }


}
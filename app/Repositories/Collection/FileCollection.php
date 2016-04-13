<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Repositories\Collection;

use App\Repositories\Collection\Exceptions\DataSpoiledException;
use Illuminate\Support\Collection;
use League\Flysystem\Filesystem;

class FileCollection
{
    /**
     * @var Filesystem
     */
    protected $disk;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct()
    {
        $this->disk = app('disk');
    }

    public function load($file)
    {
        $data = [];

        if ($this->disk->has($file)) {
            $data = unserialize($this->disk->read($file));

            if (!is_array($data)) {
                throw new DataSpoiledException;
            }
        }

        $this->collection = new Collection($data);
    }

    public function collection()
    {
        return $this->collection;
    }

    public function save($file)
    {
        return $this->disk->put($file,
            serialize($this->collection->toArray())
        );
    }
}
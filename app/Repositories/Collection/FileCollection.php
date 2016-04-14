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
    protected $file;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct()
    {
        $this->disk = app('disk');
    }

    public function load()
    {
        $data = [];

        if ($this->disk->has($this->file)) {
            $data = unserialize($this->disk->read($this->file));

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

    public function save()
    {
        return $this->disk->put($this->file,
            serialize($this->collection->toArray())
        );
    }
}
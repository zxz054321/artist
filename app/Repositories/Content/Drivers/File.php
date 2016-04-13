<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Repositories\Content\Drivers;

use App\Repositories\Collection\FileCollection;
use Ramsey\Uuid\Uuid;

class File extends FileCollection
{
    protected $path;
    protected $indexFile;

    public function __construct($path)
    {
        parent::__construct();

        $this->path      = $path;
        $this->indexFile = $path.'/index';

        $this->load($this->indexFile);
    }

    public function create(array $data, $id = null)
    {
        $uuid = is_null($id) ? Uuid::uuid1()->toString() : $id;
        $file = $this->contentFilePath($uuid);

        if (!$this->disk->put($file, $data['content'])) {
            return false;
        }

        unset($data['content']);

        $this->collection->prepend($data, $uuid);

        return $uuid;
    }

    protected function contentFilePath($id)
    {
        return "{$this->path}/$id.md";
    }

    public function update($primary, array $data)
    {
        $old  = $this->collection->get($primary);
        $data = array_merge($old, $data);

        if (isset($data['content'])) {
            $file = $this->contentFilePath($primary);

            if (!$this->disk->put($file, $data['content'])) {
                return false;
            }

            unset($data['content']);
        }

        $this->collection->put($primary, $data);

        return true;
    }

    public function delete($primary)
    {
        $file = $this->contentFilePath($primary);

        if (!$this->disk->delete($file)) {
            return false;
        }

        $this->collection->forget($primary);

        return true;
    }

    public function read($primary)
    {
        $data = $this->collection->get($primary);

        if (!$data) {
            return null;
        }

        $file            = $this->contentFilePath($primary);
        $data['content'] = $this->disk->read($file);

        return $data;
    }

    /**
     * @param string|array $primary
     * @return bool
     */
    public function exists($primary)
    {
        if (!is_array($primary)) {
            return $this->collection->has($primary);
        }

        return $this->collection->contains(
            key($primary),
            current($primary)
        );
    }

    public function save($file = null)
    {
        if (is_null($file)) {
            $file = $this->indexFile;
        }

        return parent::save($file);
    }
}
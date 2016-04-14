<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Repositories\Content\Drivers;

use App\Repositories\Collection\FileCollection;
use App\Repositories\Content\DriverContract;
use Ramsey\Uuid\Uuid;

class File extends FileCollection implements DriverContract
{
    protected $path;

    public function __construct($path)
    {
        parent::__construct();

        $this->path = $path;
        $this->file = $path.'/index';

        $this->load();
    }

    /**
     * @param array $data
     * @param string|null $id for recreate
     * @return string|false uuid
     */
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

    /**
     * @param $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $old  = $this->collection->get($id);
        $data = array_merge($old, $data);

        if (isset($data['content'])) {
            $file = $this->contentFilePath($id);

            if (!$this->disk->put($file, $data['content'])) {
                return false;
            }

            unset($data['content']);
        }

        $this->collection->put($id, $data);

        return true;
    }

    /**
     * @param $id
     * @return array|null
     */
    public function read($id)
    {
        $data = $this->collection->get($id);

        if (!$data) {
            return null;
        }

        $file            = $this->contentFilePath($id);
        $data['content'] = $this->disk->read($file);

        return $data;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $file = $this->contentFilePath($id);

        if (!$this->disk->delete($file)) {
            return false;
        }

        $this->collection->forget($id);

        return true;
    }

    public function exists($condition)
    {
        if (!is_array($condition)) {
            return $this->collection->has($condition);
        }

        return $this->collection->contains(
            key($condition),
            current($condition)
        );
    }
}
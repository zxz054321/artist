<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Repositories\Content;

use App\Repositories\Content\Drivers\File;
use App\Repositories\Content\Exceptions\NotFoundException;
use App\Repositories\Content\Exceptions\RouteExistsException;

class Content
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';

    /**
     * @var File
     */
    protected $driver;

    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function collection()
    {
        return $this->driver->collection();
    }

    public function create(array $data,$id=null)
    {
        if ($this->driver->exists(['route' => $data['route']])) {
            throw new RouteExistsException('Content already exists!');
        }

        return $this->driver->create($data,$id);
    }

    public function update($primary, array $data)
    {
        if (!$this->driver->exists($primary)) {
            throw new NotFoundException('Content not found!');
        }

        return $this->driver->update($primary, $data);
    }

    public function delete($primary)
    {
        return $this->driver->delete($primary);
    }

    /**
     * @param $primary
     * @return array
     */
    public function read($primary)
    {
        return $this->driver->read($primary);
    }

    public function exists($primary)
    {
        return $this->driver->exists($primary);
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->driver, $method], $arguments);
    }
}
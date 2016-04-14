<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Repositories\Content;

use App\Repositories\Content\Exceptions\NotFoundException;
use App\Repositories\Content\Exceptions\RouteExistsException;

/**
 * Class Content
 * @package App\Repositories\Content
 * @method bool save()
 */
class Content
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';

    /**
     * @var DriverContract
     */
    protected $driver;

    public function __construct(DriverContract $driver)
    {
        $this->driver = $driver;
    }

    public function create(array $data, $id = null)
    {
        if ($this->driver->exists(['route' => $data['route']])) {
            throw new RouteExistsException('Content already exists!');
        }

        return $this->driver->create($data, $id);
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
        if (!$this->driver->exists($primary)) {
            throw new NotFoundException('Content not found!');
        }

        return $this->driver->delete($primary);
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->driver, $method], $arguments);
    }
}
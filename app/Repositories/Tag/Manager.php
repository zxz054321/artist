<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Repositories\Tag;

use Illuminate\Support\Collection;

/**
 * Class Manager
 * @package App\Repositories\Tag
 * @method Collection collection
 * @method setCollection(Collection $collection)
 * @method Tag make($tag)
 * @method Tag tag($name)
 * @method bool exists($tag)
 */
class Manager
{
    /**
     * @var DriverContract
     */
    protected $driver;

    public function __construct(DriverContract $driver)
    {
        $this->driver = $driver;
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->driver, $method], $arguments);
    }
}
<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Repositories\Tag;

use Illuminate\Support\Collection;

interface DriverContract
{
    /**
     * @return Collection
     */
    public function collection();

    /**
     * @param Collection $collection
     * @return void
     */
    public function setCollection($collection);

    /**
     * Create a tag if not exists
     * @param string $tag
     * @return Tag
     */
    public function make($tag);

    /**
     * Get the tag
     * @param string $name
     * @return Tag
     * @throws TagNotFoundException
     */
    public function tag($name);

    /**
     * @param string $tag
     * @return bool
     * @throws TagNotFoundException
     */
    public function destory($tag);

    public function exists($tag);

    /**
     * @return bool
     */
    public function save();
}
<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Repositories\Content;

use Illuminate\Support\Collection;

interface DriverContract
{
    /**
     * @return Collection
     */
    public function collection();

    /**
     * @param array $data
     * @param string|null $id for recreate
     * @return string|false uuid
     */
    public function create(array $data, $id = null);

    /**
     * @param $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data);

    /**
     * @param $id
     * @return array|null
     */
    public function read($id);

    /**
     * @param $id
     * @return bool
     */
    public function delete($id);

    public function exists($condition);

    /**
     * @return bool
     */
    public function save();
}
<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Services;

use App\Repositories\Content\Content as ContentRepository;
use App\Repositories\Content\Drivers\File;
use App\Repositories\Content\Exceptions\NotFoundException;
use App\Repositories\Content\Exceptions\RouteExistsException;

class Content
{
    const EXISTS_AS_REVEALED = 'revealed';
    const EXISTS_AS_UNREVEALED = 'unrevealed';
    /**
     * @var ContentRepository
     */
    protected $revealed;
    /**
     * @var ContentRepository
     */
    protected $unrevealed;

    public function __construct()
    {
        $this->revealed   = app('content.revealed');
        $this->unrevealed = app('content.unrevealed');
    }

    public function getRevealed()
    {
        return $this->revealed;
    }

    public function getUnrevealed()
    {
        return $this->unrevealed;
    }

    public function all()
    {
        return [
            'revealed'   => $this->revealed->collection()->all(),
            'unrevealed' => $this->unrevealed->collection()->all(),
        ];
    }

    public function get($id)
    {
        $data = $this->unrevealed->read($id);

        if (!$data) {
            $data = $this->revealed->read($id);
        }

        return $data;
    }

    /**
     * @param $primary
     * @param array $data
     * @return bool
     * @throws NotFoundException
     */
    public function update($primary, array $data)
    {
        $handler = $this->exists($primary);

        if (!$handler) {
            throw new NotFoundException('Content not found!');
        }

        if (isset($data['status'])) {
            /*
             * is it the first publication of a content?
             */
            if (self::EXISTS_AS_UNREVEALED == $handler) {
                /*
                 * the first time to reveal it
                 */
                $toPublish = ContentRepository::STATUS_PUBLISHED == $data['status'];

                if ($toPublish) {
                    return $this->toPublish($primary, $data);
                }
            } elseif (ContentRepository::STATUS_DRAFT == $data['status']) {
                /*
                 * create a new draft copy of the published content
                 */
                return $this->newDraft($primary, $data);
            } elseif (true == $this->unrevealed->exists($primary)) {
                /*
                 * publish(delete) the draft copy of the published content
                 */
                $result =
                    $this->unrevealed->delete($primary)
                and
                $this->unrevealed->save();

                if (!$result) {
                    return false;
                }
            }
        }

        /** @var ContentRepository $handler */
        $handler = $this->$handler;

        return $handler->update($primary, $data) && $handler->save();
    }

    /**
     * @param $condition
     * @return false|string
     */
    public function exists($condition)
    {
        if ($this->revealed->exists($condition)) {
            return self::EXISTS_AS_REVEALED;
        }

        if ($this->unrevealed->exists($condition)) {
            return self::EXISTS_AS_UNREVEALED;
        }

        return false;
    }

    protected function toPublish($primary, array $data)
    {
        /*
              * finally the draft becomes published content
              * fetch it from unrevealed
              */
        $data['created_at'] = $data['updated_at'];
        $old                = $this->unrevealed->read($primary);
        $new                = array_merge($old, $data);

        unset($new['updated_at']);

        /*
         * do some cleanup
         */
        $this->unrevealed->delete($primary);

        /*
         * then put it into revealed
         *
         */
        $this->revealed->create($new, $primary);

        return
            $this->unrevealed->save()
            &&
            $this->revealed->save();
    }

    protected function newDraft($primary, array $data)
    {
        if ($this->unrevealed->exists($primary)) {
            $this->unrevealed->update($primary, $data);
        } else {
            $this->unrevealed->create($data, $primary);
        }

        return $this->unrevealed->save();
    }

    /**
     * @param array $data
     * @return false|string
     * @throws RouteExistsException
     */
    public function create(array $data)
    {
        if ($this->exists(['route' => $data['route']])) {
            throw new RouteExistsException('Content already exists!');
        }

        $handler = ContentRepository::STATUS_DRAFT == $data['status'] ?
            $this->unrevealed :
            $this->revealed;

        $id = $handler->create($data);

        return $handler->save() ? $id : false;
    }
}
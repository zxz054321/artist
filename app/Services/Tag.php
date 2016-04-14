<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Services;

use App\Repositories\Tag\Drivers\File;
use App\Repositories\Tag\Manager;
use App\Repositories\Tag\Tag as TagItem;

class Tag
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new Manager(new File());
    }

    public function manager()
    {
        return $this->manager;
    }

    public function unreference($referrer, array $tags)
    {
        foreach ($tags as $tag) {
            $ref = $this->manager
                ->tag($tag)
                ->unreference($referrer);

            if ($ref === 0) {
                $this->manager->destory($tag);
            }
        }

        return $this;
    }

    public function sync($referrer, array $tags)
    {
        $synchronized = $this->manager->collection()
            /*
             * We deal with these tags later
             */
            ->except($tags)
            /*
             * do some cleanup
             */
            ->each(function (TagItem $item) use ($referrer) {
                $item->unreference($referrer);
            })
            /*
             * remove empty tags
             */
            ->reject(function (TagItem $item) {
                return $item->isEmpty();
            });

        $this->manager->setCollection($synchronized);

        return $this->make($referrer, $tags);
    }

    public function make($referrer, array $tags)
    {
        foreach ($tags as $tag) {
            $this->manager
                ->make($tag)
                ->referTo($referrer);
        }

        return $this;
    }

    public function save()
    {
        return $this->manager->save();
    }
}
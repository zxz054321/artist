<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Repositories\Tag;

class Tag
{
    protected $referrers;

    public function __construct($referrers = [])
    {
        $this->referrers = $referrers;
    }

    /**
     * @param string|array $referrer
     * @return $this
     */
    public function refer($referrer)
    {
        if (is_array($referrer)) {
            foreach ($referrer as $item) {
                $this->singleRefer($item);
            }
        } else {
            $this->singleRefer($referrer);
        }

        return $this;
    }

    protected function singleRefer($referrer)
    {
        if (!$this->exists($referrer)) {
            $this->referrers[] = (string)$referrer;
        }
    }

    public function exists($name)
    {
        return in_array((string)$name, $this->referrers, true);
    }

    public function unreference($referrer)
    {
        $key = array_search((string)$referrer, $this->referrers, true);

        if (false !== $key) {
            unset($this->referrers[ $key ]);
        }

        return count($this->referrers);
    }

    public function count()
    {
        return count($this->referrers);
    }

    public function all()
    {
        return $this->referrers;
    }
}
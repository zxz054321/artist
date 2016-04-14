<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Repositories\Tag;

use Illuminate\Support\Collection;

/**
 * Class Tag
 * represents a single tag
 * @package App\Repositories\Tag
 */
class Tag extends Collection
{
    public function unreference($referrer)
    {
        $key = array_search((string)$referrer, $this->items, true);

        if (false !== $key) {
            unset($this->items[ $key ]);
        }

        return count($this->items);
    }

    /**
     * @param string|array $referrer
     * @return $this
     */
    public function referTo($referrer)
    {
        if (is_array($referrer)) {
            foreach ($referrer as $item) {
                $this->uniqueAdd($item);
            }
        } else {
            $this->uniqueAdd($referrer);
        }

        return $this;
    }

    protected function uniqueAdd($referrer)
    {
        if (!$this->contains($referrer)) {
            $this->push((string)$referrer);
        }
    }

}
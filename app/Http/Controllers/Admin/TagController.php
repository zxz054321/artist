<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Repositories\Content\Content;
use App\Repositories\Tag\Manager as TagManager;
use Parsedown;
use Symfony\Component\HttpFoundation\Request;

class TagController
{
    public function index()
    {
        $mgr = new TagManager();

        return app()->json([
            'tags' => $mgr->toArray(),
        ]);
    }
}
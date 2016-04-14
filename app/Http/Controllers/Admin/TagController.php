<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Repositories\Content\Content;
use App\Repositories\Tag\Manager as TagManager;
use App\Services\Tag;
use Parsedown;
use Symfony\Component\HttpFoundation\Request;

class TagController
{
    public function index()
    {
        $service = new Tag();

        return app()->json([
            'tags' => $service->manager()->collection()->toArray(),
        ]);
    }
}
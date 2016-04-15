<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Http\Controllers;

use App\Repositories\Content\Content;
use Parsedown;

class FrontController
{
    /**
     * @var Content
     */
    protected $content;

    public function __construct()
    {
        $this->content = app('content.revealed');
    }

    public function index()
    {
        if ($this->content->collection()->isEmpty()) {
            abort(503, 'There is no content to display');
        }

        return view('home', [
            'headline' => $this->content->collection()->shift(),
            'contents' => $this->content->collection()->all(),
        ]);
    }

    public function show($route)
    {
        $id = $this->content->collection()
            ->search(function ($item) use ($route) {
                return $item['route'] == $route;
            });

        $markdown        = new Parsedown();
        $data            = $this->content->read($id);
        $data['content'] = $markdown->text($data['content']);

        return view('article', [
            'article' => $data,
        ]);
    }
}
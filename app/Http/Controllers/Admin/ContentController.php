<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Services\Tag;
use Symfony\Component\HttpFoundation\Request;

class ContentController
{
    protected $service;

    public function __construct()
    {
        $this->service = new \App\Services\Content();
    }

    public function all()
    {
        return app()->json($this->service->all());
    }

    public function raw($id)
    {
        return app()->json(['raw' => $this->service->get($id)]);
    }

    public function check(Request $request)
    {
        $route = $request->get('route');

        return app()->json([
            'exists' => $this->service->getRevealed()
                ->exists(['route' => $route]),
        ]);
    }

    public function update(Request $request, $id)
    {
        //TODO update tag references
        $data = $this->decodeRequest($request);

        if (!$this->service->exists($id)) {
            return app()->json([
                'result' => false,
                'err'    => 'notfound',
            ]);
        }

        /** @var Tag $tagService */
        $tagService = app('tag');
        $tags       = $this->parseTags($data->tags);

        $arr               = $this->parseData($data);
        $arr['tags']       = $tags;
        $arr['updated_at'] = time();

        $result = $this->service->update($id, $arr)
        and
        $tagService->sync($id, $tags)->save();

        return app()->json([
            'result' => $result,
        ]);
    }

    protected function decodeRequest(Request $request)
    {
        return json_decode($request->getContent());
    }

    protected function parseTags($str)
    {
        if (empty($str)) {
            return [];
        }

        if (is_array($str)) {
            return $str;
        }

        $tags = explode(',', $str);

        return $tags;
    }

    protected function parseData($data)
    {
        $arr  = [];
        $list = [
            'title',
            'route',
            'summary',
            'content',
            'cover',
            'status',
        ];

        foreach ($list as $item) {
            if (isset($data->$item)) {
                $arr[ $item ] = $data->$item;
            }
        }

        return $arr;
    }

    public function delete($id)
    {
        /** @var Tag $tagService */
        $tagService = app('tag');

        $tags = $this->service->getRevealed()->read($id)['tags'];

        if ($tags) {
            $tagService->unreference($id, $tags);
        }

        $tags = $this->service->getUnrevealed()->read($id)['tags'];

        if ($tags) {
            $tagService->unreference($id, $tags);
        }

        return app()->json([
            'result' => $tagService->save() && $this->service->delete($id),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->decodeRequest($request);

        if ($this->service->exists(['route' => $data->route])) {
            return app()->json([
                'result' => false,
                'err'    => 'route.exists',
            ]);
        }

        /** @var Tag $tagService */
        $tagService = app('tag');
        $tags       = $this->parseTags($data->tags);

        $arr               = $this->parseData($data);
        $arr['tags']       = $tags;
        $arr['created_at'] = time();

        $id = $this->service->create($arr);

        $result =
            $id
            &&
            $tagService->sync($data->route, $tags)->save();

        return app()->json([
            'result' => $result,
            'id'     => $id,
        ]);
    }


}
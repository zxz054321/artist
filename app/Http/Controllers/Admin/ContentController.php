<?php
/**
 * Author: Abel Halo <zxz054321@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Services\Content;
use Symfony\Component\HttpFoundation\Request;

class ContentController
{
    protected $service;

    public function __construct()
    {
        $this->service = new Content();
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

    public function delete($id)
    {
        return app()->json([
            'result' => $this->service->delete($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $this->decodeRequest($request);

        if (!$this->service->exists($id)) {
            return app()->json([
                'result' => false,
                'err'    => 'notfound',
            ]);
        }

        $tags = $this->parseTags($data->tags);

        $arr               = $this->parseData($data);
        $arr['tags']       = $tags;
        $arr['updated_at'] = time();

        $result = $this->service->update($id, $arr);

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


    public function store(Request $request)
    {
        $data = $this->decodeRequest($request);

        if ($this->service->exists(['route' => $data->route])) {
            return app()->json([
                'result' => false,
                'err'    => 'route.exists',
            ]);
        }

        $tags = $this->parseTags($data->tags);

        $arr               = $this->parseData($data);
        $arr['tags']       = $tags;
        $arr['created_at'] = time();

        $id = $this->service->create($arr);

        return app()->json([
            'result' => (bool)$id,
            'id'     => $id,
        ]);
    }
}
<?php

namespace App\Services;

use App\Models\Link;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LinkService
{
    public function createLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $link = new Link();
        $link->url = $request->input('url');
        $link->code = $this->generateRandomString();
        $link->clicks = 0;

        if ($request->input('name') == null) {
            $link->name = $link->code;
        } else {
            $link->name = $request->input('name');
        }

        $link->save();


        return response()->json($link, 201);
    }

    public function generateRandomString($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    public function getAllLinks()
    {
        $links = Link::all();
        return response()->json($links);
    }

    public function getByFilter($request)
    {
        $filterType = $request->query('filter');

        if ($filterType === 'clicks') {
            $links = Link::orderBy('clicks', 'desc')->get();
        } elseif ($filterType === 'name') {
            $links = Link::orderBy('name')->get();
        } else {
            return response()->json(['error' => 'Invalid filter type'], 400);
        }

        return response()->json($links);
    }
}

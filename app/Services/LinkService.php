<?php

namespace App\Services;

use App\Models\Link;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::table('links')
            ->increment('views', 1);
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
        } elseif ($filterType === 'views') {
            $links = Link::orderBy('views', 'desc')->get();
        } else {
            return response()->json(['error' => 'Invalid filter type'], 400);
        }

        return response()->json($links);
    }

    public function incrementClicks(Request $request, $id)
    {
        $link = Link::find($id);

        if (!$link) {
            return response()->json(['error' => 'Link not found'], 404);
        }

        $link->increment('clicks');
        $link->save();

        return response()->json(['message' => 'Clicks incremented'], 200);
    }

    public function getSummary()
    {
        $totalClicks = Link::sum('clicks');
        $totalViews = Link::sum('views');
        $totalLinks = Link::count();
        $avgCtr = (ceil(($totalClicks / $totalViews) * 100));

        $data = [
            'totalClicks' => $totalClicks,
            'totalViews' => $totalViews,
            'totalLinks' => $totalLinks,
            'avgCtr' => $avgCtr
        ];

        return response()->json($data, 200);
    }
}

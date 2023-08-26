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

    public function getByFilter(Request $request)
    {
        $filterType = $request->query('filter');
        $searchTerm = $request->query('search');

        $query = Link::query();

        if ($filterType === 'clicks') {
            $query->orderBy('clicks', 'desc');
        } elseif ($filterType === 'name') {
            $query->orderBy('name');
        } elseif ($filterType === 'views') {
            $query->orderBy('views', 'desc');
        } elseif ($filterType === 'updated') {
            $query->orderBy('updated_at', 'desc');
        } else {
            return response()->json(['error' => 'Invalid filter type'], 400);
        }


        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('url', 'like', '%' . $searchTerm . '%');
            });
        }


        $links = $query->get();

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

        if ($totalViews > 0) {
            $avgCtr = (ceil(($totalClicks / $totalViews) * 100));
        } else {
            $avgCtr = 0;
        }

        $data = [
            'totalClicks' => $totalClicks,
            'totalViews' => $totalViews,
            'totalLinks' => $totalLinks,
            'avgCtr' => $avgCtr
        ];

        return response()->json($data, 200);
    }

    public function deleteLink($id)
    {
        $link = Link::find($id);

        if ($link) {
            $link->delete();
        } else {
            return response()->json(['error' => 'Link not found'], 404);
        }
    }

    public function getLinkById($id)
    {
        $link = Link::find($id);

        if ($link) {
            return response()->json($link);
        } else {
            return response()->json(['error' => 'Link not found'], 404);
        }
    }

    public function getByCode($code)
    {
        // Busca o link pelo código
        $link = Link::where('code', $code)->first();

        if (!$link) {
            return response()->json(['message' => 'Link não encontrado'], 404);
        }

        return response()->json($link);
    }

    public function updateLink(Request $request, $id)
    {
        // Validação dos campos
        $request->validate([
            'name' => 'required|string',
            'url' => 'required|url',
        ]);

        // Busca o link pelo ID
        $link = Link::findOrFail($id);

        // Atualiza os campos
        $link->update([
            'name' => $request->input('name'),
            'url' => $request->input('url'),
        ]);

        return response()->json(['message' => 'Link atualizado com sucesso']);
    }
}

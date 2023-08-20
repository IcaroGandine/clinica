<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Services\LinkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LinkController extends Controller
{
    protected $linkService;

    public function __construct(LinkService $linkService)
    {
        $this->linkService = $linkService;
    }

    /**
     * 
     */
    public function getAll()
    {
        return $this->linkService->getAllLinks();
    }
    /**
     * 
     */
    public function getByFilter(Request $request)
    {
        return $this->linkService->getByFilter($request);
    }

    /**
     * 
     */
    public function create(Request $request)
    {
        return $this->linkService->createLink($request);
    }

    /**
     * 
     */
    public function incrementClicks(Request $request, $id)
    {
        return $this->linkService->incrementClicks($request, $id);
    }

    /**
     * 
     */
    public function show(string $id)
    {
        //
    }

    /**
     *
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     *
     */
    public function destroy(string $id)
    {
        //
    }
}

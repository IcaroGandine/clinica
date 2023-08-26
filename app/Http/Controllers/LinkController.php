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

    public function getSummary()
    {
        return $this->linkService->getSummary();
    }

    /**
     * 
     */

    public function delete($id)
    {
        return $this->linkService->deleteLink($id);
    }

    /**
     * 
     */

    public function getLinkById($id)
    {
        return $this->linkService->getLinkById($id);
    }

    /**
     * 
     */

    public function update(Request $request, $id)
    {
        return $this->linkService->updateLink($request, $id);
    }
}

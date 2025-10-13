<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    protected SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Display search results
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = $request->input('q', '');
        $startTime = microtime(true);
        
        $results = $this->searchService->search($query, auth()->user());
        
        $searchTime = round((microtime(true) - $startTime) * 1000, 2); // Convert to milliseconds

        return view('search.index', [
            'query' => $query,
            'results' => $results,
            'searchTime' => $searchTime,
        ]);
    }
}

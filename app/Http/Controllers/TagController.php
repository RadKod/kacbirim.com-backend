<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $term = $request->get('term');
        $page = $request->get('page');
        $resultCount = 25;
        $offset = ($page - 1) * $resultCount;

        $tags = Tags::query()
            ->where('name', 'LIKE', '%' . $term . '%')
            ->orderBy('name')->skip($offset)->take($resultCount)
            ->get(['id', 'name as text']);

        $count = Tags::count();
        $endCount = $offset + $resultCount;
        $morePages = $endCount > $count;

        $results = array(
            "results" => $tags,
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);
    }
}

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
        $tags = Tags::query()
            ->where('name', 'LIKE', '%' . $term . '%')
            ->orderBy('name')
            ->pluck('name')->toArray();
        return response()->json($tags);
    }
}

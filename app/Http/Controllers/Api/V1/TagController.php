<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagCollection;
use App\Models\Tags;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * @param Request $request
     * @return TagCollection
     */
    public function index(Request $request): TagCollection
    {
        $paginate = $request->get('limit') ?: 15;
        $tags = Tags::filter()->paginate($paginate)->appends(request()->except('page'));
        return new TagCollection($tags);
    }
}

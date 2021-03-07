<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagCollection;
use App\Models\Tags;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * @return TagCollection
     */
    public function index(): TagCollection
    {
        $posts = Tags::filter()->paginate()->appends(request()->except('page'));
        return new TagCollection($posts);
    }
}

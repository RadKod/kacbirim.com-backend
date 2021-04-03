<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * @param Request $request
     * @return PostCollection
     */
    public function index(Request $request): PostCollection
    {
        $paginate = $request->get('limit') ?: 15;
        $posts = Post::query()->with([
            'tags', 'countries', 'tags.tag', 'countries.country', 'countries.country.country_wages'
        ])->filter()->paginate($paginate)->appends(request()->except('page'));
        return new PostCollection($posts);
    }

    /**
     * @param $id_or_slug
     * @return JsonResponse
     */
    public function show($id_or_slug): JsonResponse
    {
        $post = Post::with([
            'tags', 'countries', 'tags.tag', 'countries.country'
        ])->where('id', $id_or_slug)->orWhere('slug', $id_or_slug)->first();
        if ($post) {
            return response()->json([
                'success' => true,
                'message' => 'post found',
                'data' => new PostResource($post)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'post not found',
            'data' => null,
        ]);
    }
}

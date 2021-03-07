<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * @return PostCollection
     */
    public function index(): PostCollection
    {
        $posts = Post::with([
            'tags', 'countries', 'tags.tag', 'countries.country'
        ])->filter()->paginate()->appends(request()->except('page'));
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
                'status' => 'success',
                'message' => 'post found',
                'data' => new PostResource($post)
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'post not found',
            'data' => null,
        ]);
    }
}

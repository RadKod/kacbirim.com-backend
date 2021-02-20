<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request){
        $posts = Post::query()
            ->with(['countries', 'countries.country'])
            ->paginate(10);
        return view('post.index', compact('posts'));
    }

    public function create(Request $request){
        return view('post.create');
    }
}

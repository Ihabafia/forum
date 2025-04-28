<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return inertia('Posts/PostIndex', [
            'posts' => PostResource::collection(Post::latest('id')->paginate()),
        ]);
    }

    public function create() {}

    public function store(Request $request) {}

    public function show(Post $post)
    {
        return inertia('Posts/PostShow', [
            'post' => PostResource::make($post),
        ]);
    }

    public function edit(Post $post) {}

    public function update(Request $request, Post $post) {}

    public function destroy(Post $post) {}
}

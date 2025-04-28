<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return inertia('Posts/PostIndex', [
            'posts' => fn () => PostResource::collection(Post::includeUser()->latest()->latest('id')->paginate()),
        ]);
    }

    public function create() {}

    public function store(Request $request) {}

    public function show(Post $post)
    {
        $post->load(['user']);

        return inertia('Posts/PostShow', [
            'post' => fn () => PostResource::make($post),
            'comments' => fn () => CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(5)),
        ]);
    }

    public function edit(Post $post) {}

    public function update(Request $request, Post $post) {}

    public function destroy(Post $post) {}
}

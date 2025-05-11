<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostModalController extends Controller
{
    public function index() {}

    public function create(?Post $post)
    {
        return inertia()->modal('Posts/AddEditPost', [
            'post' => fn () => $post,
        ])->baseRoute('posts.index');
    }

    public function store(Request $request) {}

    public function show(Post $post) {}

    public function edit(Post $post) {}

    public function update(Request $request, Post $post) {}

    public function destroy(Post $post) {}
}

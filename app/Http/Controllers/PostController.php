<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Str;

class PostController extends Controller
{
    use Authorizable;

    public function index()
    {
        return inertia('Posts/PostIndex', [
            'posts' => fn () => PostResource::collection(Post::includeUser()->latest()->latest('id')->paginate()),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:10', 'max:120'],
            'body' => ['required', 'string', 'min:100', 'max:10000'],
        ]);

        $post = Post::create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return redirect()->to($post->showRoute())
            ->with('success', 'Post created successfully.');
    }

    public function create(?Post $post)
    {
        Gate::authorize('create', $post);

        return inertia()->modal('Posts/AddEditPost', [
            'post' => fn () => $post,
        ])->baseRoute('posts.index');
    }

    public function edit(Post $post)
    {
        $post->load(['user']);

        return inertia()->modal('Posts/AddEditPost', [
            'post' => fn () => $post,
        ])->baseRoute('posts.index');
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:10', 'max:120'],
            'body' => ['required', 'string', 'min:10', 'max:10000'],
        ]);

        $post->update($data);

        return redirect()->route('posts.show', ['post' => $post])
            ->with('success', 'Post updated successfully.');
    }

    public function show(Request $request, Post $post)
    {
        if (! Str::contains($post->showRoute(), $request->getRequestUri())) {
            return redirect()->to($post->showRoute($request->query()), status: 301);
        }

        $post->load(['user']);

        return inertia('Posts/PostShow', [
            'post' => fn () => PostResource::make($post),
            'comments' => fn () => CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(5)),
        ]);
    }

    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return to_route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    public function delete(Post $post)
    {
        return inertia()->modal('DeletePost', [
            'post' => fn () => $post,
        ])->baseRoute('posts.show', $post->id);
    }
}

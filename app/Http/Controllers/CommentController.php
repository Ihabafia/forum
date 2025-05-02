<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Post $post)
    {
        Gate::authorize('create', Comment::class);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2500'],
        ]);

        Comment::create([
            ...$validated,
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
        ]);

        return to_route('posts.show', $post)
            ->with('success', 'Comment added successfully.');
    }

    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2500'],
        ]);

        $comment->update($validated);

        return to_route('posts.show', ['post' => $comment->post_id, 'page' => $request->query('page')])
            ->with('success', 'Comment updated successfully.');
    }

    public function destroy(Request $request, Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();
        /* $this->authorize('delete', $comment); */

        return to_route('posts.show', ['post' => $comment->post_id, 'page' => $request->query('page')])
            ->with('success', 'Comment deleted successfully.');
    }
}

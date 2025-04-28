<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Post $post)
    {
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

    public function destroy(Request $request, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();
        /* $this->authorize('delete', $comment); */

        return to_route('posts.show', $comment->post_id)
            ->with('success', 'Comment deleted successfully.');
    }
}

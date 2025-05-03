<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;

class CommentModalController extends Controller
{
    public function edit(Comment $comment)
    {
        $comment->load(['post', 'user']);

        return inertia()->modal('EditComment', [
            'comment' => fn () => $comment,
        ])->baseRoute('posts.show', $comment->post_id);
    }

    public function create(Post $post)
    {
        $post->load(['user']);

        return inertia()->modal('CreateComment', [
            'comment' => fn () => $post,
        ])->baseRoute('posts.show', $post->id);
    }
}

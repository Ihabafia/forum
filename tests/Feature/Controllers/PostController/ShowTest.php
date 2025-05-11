<?php

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;

it('it can show a post', function () {
    $post = Post::factory()->create();

    $this->get($post->showRoute())
        ->assertInertiaComponent('Posts/PostShow');
});

it('passes the post to the view', function () {
    $post = Post::factory()->create();

    $post->load(['user']);

    $this->get($post->showRoute())
        ->assertHasResource('post', PostResource::make($post));
});

it('passes comments to the view', function () {
    $post = Post::factory()->create();
    $comments = Comment::factory(3)->for($post)->create();

    $comments->load(['user']);

    $this->get($post->showRoute())
        ->assertHasPaginatedResource('comments', CommentResource::collection($comments->reverse()));
});

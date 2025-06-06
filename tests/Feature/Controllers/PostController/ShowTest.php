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

    $post->load(['user', 'topic']);

    $this->get($post->showRoute())
        ->assertHasResource('post', PostResource::make($post)->withLikePermission()->withAddEditPermission());
});

it('passes comments to the view', function () {
    $post = Post::factory()->create();
    $comments = Comment::factory(3)->for($post)->create();

    $comments->load(['user']);

    $expectedResource = CommentResource::collection($comments->reverse());

    $expectedResource->collection->transform(fn (CommentResource $resource) => $resource->withLikePermission());

    $this->get($post->showRoute())
        ->assertHasPaginatedResource('comments', $expectedResource);
});

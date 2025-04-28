<?php

use App\Http\Resources\PostResource;
use App\Models\Post;

it('it can show a post', function () {
    $post = Post::factory()->create();

    $this->get(route('posts.show', $post))
        ->assertInertiaComponent('Posts/PostShow');
});

it('passes the post to the view', function () {
    $post = Post::factory()->create();

    $this->get(route('posts.show', $post))
        ->assertHasResource('post', PostResource::make($post));
});

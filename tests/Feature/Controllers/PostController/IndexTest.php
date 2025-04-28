<?php

use App\Http\Resources\PostResource;
use App\Models\Post;

it('it should return the correct component', function () {
    $this->get(route('home'))
        ->assertInertiaComponent('Welcome');
});

it('passes posts to the view', function () {
    // Arrange
    $posts = Post::factory(3)->create();

    $posts->load(['user']);

    // Act & Assert
    $this->get(route('posts.index'))
        ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));
});

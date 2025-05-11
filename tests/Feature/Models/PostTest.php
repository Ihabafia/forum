<?php

use App\Models\Post;

it('uses title case for titles', function () {
    // Arrange
    $post = Post::factory()->create([
        'title' => 'Hello, how are you?',
    ]);

    // Act & Assert
    expect($post->title)->toBe('Hello, How Are You?');
});

it('can generate a route for the show page', function () {
    // Arrange
    $post = Post::factory()->create();

    // Act & Assert
    expect($post->showRoute())->toBe(route('posts.show', ['post' => $post, 'slug' => Str::slug($post->title)]));
});

it('can generate additional query parameters on the show route', function () {
    // Arrange
    $post = Post::factory()->create();

    // Act & Assert
    expect($post->showRoute(['page' => '2']))
        ->toBe(route('posts.show', ['post' => $post, 'slug' => Str::slug($post->title), 'page' => '2']));
});

it('will redirect if the slug is incorrect', function () {
    // Arrange
    $post = Post::factory()->create([
        'title' => 'Hello, how are you?',
    ]);

    // Act & Assert
    $this->get(route('posts.show', [$post, 'foo-bar', 'page' => 2]))
        ->assertRedirect($post->showRoute(['page' => 2]));
});

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
        ->toBe(route('posts.show', ['post' => $post, 'slug' => $post->slug(), 'page' => '2']));
});

it('will redirect if the slug is incorrect', function (string $incorrectSlug) {
    // Arrange
    $post = Post::factory()->create([
        'title' => 'Hello, how are you?',
    ]);

    // Act & Assert
    $this->get(route('posts.show', [$post, $incorrectSlug, 'page' => 2]))
        ->assertRedirect($post->showRoute(['page' => 2]));
})->with([
    'foo-bar',
    'hello',
]);

it('generates html from the markdown body', function () {
    // Arrange
    $post = Post::factory()->make([
        'body' => '## Hello, how are you?',
    ]);

    $post->save();

    // Act & Assert
    expect($post->html)->toEqual(str($post->body)->markdown());
});

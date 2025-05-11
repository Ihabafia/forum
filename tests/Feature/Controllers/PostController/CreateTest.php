<?php

use App\Models\Post;
use App\Models\User;

beforeEach(function () {
    $this->validData = [
        'title' => 'Test Post, Great Post',
        'body' => 'irure minim fugiat ut voluptate ex nisi elit et minim anim Lorem ex et adipisicing ad sint velit consectetur occaecat',
    ];
});

it('requires authentication', function () {
    $this->post(route('posts.store'))
        ->assertRedirect(route('login'));
});

it('requires a valid data', function (array $badData, array|string $errors) {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = $this->actingAs($user)
        ->post(route('posts.store'), [...$this->validData, ...$badData]);

    // Assert
    $response->assertInvalid($errors);
})->with([
    [['title' => ''], 'title'],
    [['title' => null], 'title'],
    [['title' => true], 'title'],
    [['title' => str_repeat('a', 121)], 'title'],
    [['title' => str_repeat('a', 9)], 'title'],
    [['title' => 1], 'title'],
    [['title' => 1.5], 'title'],
    [['body' => ''], 'body'],
    [['body' => null], 'body'],
    [['body' => true], 'body'],
    [['body' => str_repeat('a', 10_001)], 'body'],
    [['body' => str_repeat('a', 99)], 'body'],
    [['body' => 1], 'body'],
    [['body' => 1.5], 'body'],
]);

it('store a post', function () {
    $this->withoutExceptionHandling();

    // Arrange
    $user = User::factory()->create();

    // Act
    $this->actingAs($user)
        ->post(route('posts.store'), $this->validData);

    // Assert
    $this->assertDatabaseHas(Post::class, [
        ...$this->validData,
        'user_id' => $user->id,
    ]);
});

it('redirects to the post show page', function () {
    $this->withoutExceptionHandling();

    // Arrange
    $user = User::factory()->create();

    // Act
    $this->actingAs($user)
        ->post(route('posts.store'), $this->validData)
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('returns the correct component', function () {
    // Arrange, Act & Assert
    $this->actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertInertiaComponent('Posts/PostIndex');
});

<?php

use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;

beforeEach(function () {
    $this->validData = [
        'title' => 'Test Post, Great Post',
        'body' => 'irure minim fugiat ut voluptate ex nisi elit et minim anim Lorem ex et adipisicing ad sint velit consectetur occaecat',
        'topic_id' => Topic::factory()->create()->id,
    ];
});

it('requires authentication', function () {
    // Arrange
    $post = Post::factory()->create($this->validData);
    // Act & Assert
    $this->put(route('posts.update', ['post' => $post]))
        ->assertRedirect(route('login'));
});

it('store a post', function () {
    $this->withoutExceptionHandling();

    // Arrange
    $user = User::factory()->create();

    // Act
    $this->actingAs($user)->post(route('posts.store'), $this->validData);

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
    $this->actingAs($user)->post(route('posts.store'), $this->validData)
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('requires a valid data', function (array $badData, array|string $errors) {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = $this->actingAs($user)
        ->post(route('posts.store'), [...$this->validData, ...$badData]);

    // Assert
    $response->assertInvalid([$errors]);
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
    [['body' => str_repeat('a', 9)], 'body'],
    [['body' => 1], 'body'],
    [['body' => 1.5], 'body'],
]);

it('returns the correct component', function () {
    // Arrange, Act & Assert
    $this->actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertInertiaComponent('Posts/PostIndex');
});

it('passes topics to the view', function () {
    // Arrange
    $user = User::factory()->create();
    $post = Post::factory()->create(value($this->validData));
    Topic::factory(3)->create();
    $topics = Topic::all();

    // Act & Assert
    $this->actingAs($user)->get(route('posts.edit', ['post' => $post]))
        ->assertHasResource('topics', TopicResource::collection($topics));
});

<?php

use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;

beforeEach(function () {
    $this->validData = fn () => [
        'title' => 'Test Post, Great Post',
        'body' => 'irure minim fugiat ut voluptate ex nisi elit et minim anim Lorem ex et adipisicing ad sint velit consectetur occaecat',
        'topic_id' => Topic::factory()->create()->id,
    ];
});

it('requires authentication', function () {
    $this->post(route('posts.store'))
        ->assertRedirect(route('login'));
});

it('requires a valid data', function (array $badData, array|string $errors) {
    // Arrange
    $user = User::factory()->create();
    $data = value($this->validData);

    // Act
    $response = $this->actingAs($user)
        ->post(route('posts.store'), [...$data, ...$badData]);

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
    [['topic_id' => null], 'topic_id'],
    [['topic_id' => -1], 'topic_id'],
    [['body' => ''], 'body'],
    [['body' => null], 'body'],
    [['body' => true], 'body'],
    [['body' => str_repeat('a', 10_001)], 'body'],
    [['body' => str_repeat('a', 99)], 'body'],
    [['body' => 1], 'body'],
    [['body' => 1.5], 'body'],
]);

it('store a post', function () {
    // Arrange
    $user = User::factory()->create();
    $data = value($this->validData);

    // Act
    $this->actingAs($user)
        ->post(route('posts.store'), $data);

    // Assert
    $this->assertDatabaseHas(Post::class, [
        ...$data,
        'user_id' => $user->id,
    ]);
});

it('redirects to the post show page', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $this->actingAs($user)
        ->post(route('posts.store'), value($this->validData))
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('returns the correct component', function () {
    // Arrange, Act & Assert
    $this->actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertInertiaComponent('Posts/PostIndex');
});

it('passes topics to the view', function () {
    // Arrange
    $user = User::factory()->create();
    $topics = Topic::factory(3)->create();

    // Act & Assert
    $this->actingAs($user)->get(route('posts.create'))
        ->assertHasResource('topics', TopicResource::collection($topics));
});

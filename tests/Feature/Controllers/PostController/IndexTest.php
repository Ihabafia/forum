<?php

use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;

it('it should return the correct component', function () {
    $this->get(route('home'))
        ->assertInertiaComponent('Welcome');
});

it('passes posts to the view', function () {
    // Arrange
    $posts = Post::factory(3)->create();

    $posts->load(['user', 'topic']);

    // Act & Assert
    $this->get(route('posts.index'))
        ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));
});

it('passes topics to the view', function () {
    // Arrange
    $topics = Topic::factory(3)->create();

    // Act & Assert
    $this->get(route('posts.index'))
        ->assertHasResource('topics', TopicResource::collection($topics));
});

it('filter to a topic', function () {
    // Arrange
    $general = Topic::factory()->create(['name' => 'General', 'slug' => 'general']);
    $posts = Post::factory(2)->for($general)->create();
    $otherPosts = Post::factory(3)->create();

    $posts->load(['user', 'topic']);

    // Act & Assert
    $this->get(route('posts.index', ['topic' => $general]))
        ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));
});

it('passes the selected topic to the view', function () {
    // Arrange
    $topic = Topic::factory()->create();

    // Act & Assert
    $this->get(route('posts.index', ['topic' => $topic]))
        ->assertHasResource('selectedTopic', TopicResource::make($topic));
});

<?php

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

it('requires authentication', function () {
    $this->post(route('likes.store', ['type' => 'post', 'id' => 1]))
        ->assertRedirect(route('login'));
});

it('allow unliking a likeable', function (Model $likeable) {
    // Arrange
    $user = User::factory()->create();

    // Act
    $this->actingAs($user)
        ->fromRoute('dashboard')
        ->post(route('likes.store', ['type' => $likeable->getMorphClass(), 'id' => $likeable->id]))
        ->assertRedirect(route('dashboard'));

    // Assert
    $this->assertDatabaseHas(Like::class, [
        'user_id' => $user->id,
        'likeable_id' => $likeable->id,
        'likeable_type' => $likeable->getMorphClass(),
    ]);

    expect($likeable->fresh()->likes_count)->toBe(1);
})->with([
    fn () => Post::factory()->create(),
    fn () => Comment::factory()->create(),
]);

it('prevents liking something you already liked.', function () {
    // Arrange
    $like = Like::factory()->create();
    $likeable = $like->likeable;

    // Act & Assert
    $this->actingAs($like->user)
        ->post(route('likes.store', ['type' => $likeable->getMorphClass(), 'id' => $likeable->id]))
        ->assertForbidden();
});

it('only allows liking supported models', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user)
        ->post(route('likes.store', ['type' => $user->getMorphClass(), 'id' => $user->id]))
        ->assertForbidden();
});

it('throws a 404 if the type is unsupported', function () {
    // Arrange & Act & Assert
    $this->actingAs(User::factory()->create())
        ->post(route('likes.store', ['type' => 'foo', 'id' => 1]))
        ->assertNotFound();
});

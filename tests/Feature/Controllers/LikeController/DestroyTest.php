<?php

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

it('requires authentication', function () {
    $this->delete(route('likes.destroy', ['type' => 'post', 'id' => 1]))
        ->assertRedirect(route('login'));
});

it('allow liking a likeable', function (Model $likeable) {
    // Arrange
    $user = User::factory()->create();
    $like = Like::factory()->for($user)->for($likeable, 'likeable')->create();

    // Act
    $this->actingAs($user)
        ->fromRoute('dashboard')
        ->delete(route('likes.destroy', ['type' => $likeable->getMorphClass(), 'id' => $likeable->id]))
        ->assertRedirect(route('dashboard'));

    // Assert
    $this->assertDatabaseEmpty(Like::class);
    expect($likeable->fresh()->likes_count)->toBe(0);
})->with([
    fn () => Post::factory()->create(['likes_count' => 1]),
    fn () => Comment::factory()->create(['likes_count' => 1]),
]);

it("prevents unliking something you haven't liked.", function () {
    // Arrange
    $likeable = Post::factory()->create();

    // Act & Assert
    $this->actingAs(User::factory()->create())
        ->delete(route('likes.destroy', ['type' => $likeable->getMorphClass(), 'id' => $likeable->id]))
        ->assertForbidden();
});

it('only allows unliking supported models', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $this->actingAs($user)
        ->delete(route('likes.destroy', ['type' => $user->getMorphClass(), 'id' => $user->id]))
        ->assertForbidden();
});

it('throws a 404 if the type is unsupported', function () {
    // Arrange & Act & Assert
    $this->actingAs(User::factory()->create())
        ->delete(route('likes.destroy', ['type' => 'foo', 'id' => 1]))
        ->assertNotFound();
});

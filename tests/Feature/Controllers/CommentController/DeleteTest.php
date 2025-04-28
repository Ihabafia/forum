<?php

use App\Models\Comment;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

it('requires authentication', function () {
    delete(route('comments.destroy', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can delete a comment', function () {
    // Arrange
    $comment = Comment::factory()->create();

    // Act
    actingAs($comment->user)->delete(route('comments.destroy', $comment));

    // Assert
    $this->assertModelMissing($comment);
});

it('redirects to the post show page', function () {
    // Arrange
    $comment = Comment::factory()->create();

    // Act & Assert
    actingAs($comment->user)
        ->delete(route('comments.destroy', $comment))
        ->assertRedirect(route('posts.show', $comment->post_id));
});

it('prevents deleting a comment you did not create', function () {
    // Arrange
    $comment = Comment::factory()->create();

    // Act & Assert
    actingAs(User::factory()->create())
        ->delete(route('comments.destroy', $comment))
        ->assertForbidden();
});

<?php

use App\Models\Comment;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;

it('requires authentication', function () {
    put(route('comments.update', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can update a comment', function () {
    $comment = Comment::factory()->create(['body' => 'Old comment body']);
    $newBody = 'New comment body';

    actingAs($comment->user)
        ->put(route('comments.update', $comment), [
            'body' => $newBody,
        ]);

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'body' => $newBody,
    ]);
});

it('redirects to the post show page', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', $comment), [
            'body' => 'New comment body',
        ])
        ->assertRedirect(route('posts.show', $comment->post_id));
});

it('redirects to the correct page of comments', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment, 'page' => 2]), [
            'body' => 'New comment body',
        ])
        ->assertRedirect(route('posts.show', ['post' => $comment->post_id, 'page' => 2]));
});

it('can not update a comment you did not create', function () {
    $comment = Comment::factory()->create();

    actingAs(User::factory()->create())
        ->put(route('comments.update', ['comment' => $comment]), [
            'body' => 'New comment body',
        ])
        ->assertForbidden();
});

it('required a valid body', function ($body) {
    // Arrange
    $comment = Comment::factory()->create();
    // Act
    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment]), [
            'body' => $body,
        ])->assertInvalid('body');
    // Assert
})->with([
    null,
    '',
    '   ',
    1,
    1.5,
    true,
    str_repeat('a', 2501),
]);

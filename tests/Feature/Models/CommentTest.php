<?php

use App\Models\Comment;

it('generates html from the markdown body', function () {
    // Arrange
    $comment = Comment::factory()->make([
        'body' => '## Hello, how are you?',
    ]);

    $comment->save();

    // Act & Assert
    expect($comment->html)->toEqual(str($comment->body)->markdown());
});

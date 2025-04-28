<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

/*Route::get('/', function () {
    return Inertia::render('HomeIndex');
})->name('home');*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::get('posts', [PostController::class, 'index'])->name('posts.index');
Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

/*Route::get('/test', function () {
    return [
        // \App\Http\Resources\UserResource::collection(\App\Models\User::all()),
        // PostResource::make(Post::includeCommentsAndUser()->whereId(200)->first()),
        CommentResource::make(Comment::includePostAndUser()->whereId(1)->first()),
    ];
});*/

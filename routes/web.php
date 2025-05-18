<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
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

    Route::resource('posts.comments', CommentController::class)->shallow()->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::get('comments/{comment}/delete', [CommentController::class, 'delete'])->name('comments.delete');

    //    Route::get('comments/{comment}/m/edit', [CommentModalController::class, 'edit'])->name('comment-modal.edit');
    //    Route::get('post/{post}/comment/m/create', [CommentModalController::class, 'create'])->name('comment-modal.create');
    //    Route::get('comments/{comment}/m/confirm', [CommentModalController::class, 'destroy'])->name('comment-modal.destroy');
    // Route::get('posts/m/create', [PostModalController::class, 'create'])->name('posts-modal.create');
    //    Route::resource('posts', PostController::class)->only(['store']);
    Route::resource('posts', PostController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::get('posts/{post}/delete', [PostController::class, 'delete'])->name('posts.delete');

    Route::post('likes/{type}/{id}', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('likes/{type}/{id}', [LikeController::class, 'destroy'])->name('likes.destroy');
});

Route::get('posts/{topic?}', [PostController::class, 'index'])->name('posts.index');
Route::get('posts/{post}/{slug}', [PostController::class, 'show'])->name('posts.show');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

/*Route::get('/test', function () {
    return [
        // \App\Http\Resources\UserResource::collection(\App\Models\User::all()),
        // PostResource::make(Post::includeCommentsAndUser()->whereId(200)->first()),
        CommentResource::make(Comment::includePostAndUser()->whereId(1)->first()),
    ];
});*/

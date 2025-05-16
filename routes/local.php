<?php

use App\Support\PostFixtures;

Route::middleware('api')->group(function () {});
Route::middleware('web')->group(function () {
    Route::get('post-content', function () {
        return PostFixtures::getFixtures()->random();
    })->name('post-content');
});

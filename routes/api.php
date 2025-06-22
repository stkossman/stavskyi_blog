<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Blog\PostController;
use App\Http\Controllers\Api\Blog\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'blog', 'as' => 'blog.'], function () {
    Route::get('posts/{slug}', [PostController::class, 'show'])->name('posts.show');
    Route::apiResource('posts', PostController::class);

    Route::apiResource('categories', CategoryController::class);
});

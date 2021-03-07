<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\CountryController;
use App\Http\Controllers\Api\V1\TagController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('api.posts');
    Route::get('/posts/{id_or_slug}', [PostController::class, 'show'])->name('api.posts.detail');
    Route::get('/countries', [CountryController::class, 'index'])->name('api.countries');
    Route::get('/tags', [TagController::class, 'index'])->name('api.tags');
});

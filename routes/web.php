<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\TagController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('posts', PostController::class)->names([
        'index' => 'posts',
        'create' => 'posts.create'
    ]);
    Route::resource('countries', CountryController::class)->names([
        'index' => 'countries'
    ]);
    Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');
    Route::get('/country/search', [CountryController::class, 'search'])->name('country.search');
});


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

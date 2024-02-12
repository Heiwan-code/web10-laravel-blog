<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/explore', [PostController::class, 'showAll'])
->middleware(['auth', 'verified'])->name('explore');

Route::get('/dashboard', [PostController::class, 'showFollowingPosts'])
->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/my-posts', [ProfileController::class, 'allPosts'])
        ->name('profile.my-posts');
    Route::get('/user/{id}/posts', [ProfileController::class, 'userPosts'])
        ->name('profile.user-posts');

    Route::get('/user/{id}/followers', [ProfileController::class, 'followers'])
        ->name('profile.user-followers');
    Route::get('/user/{id}/following', [ProfileController::class, 'following'])
        ->name('profile.user-following');
});

require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;


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

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/login/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('auth/github', [GithubController::class, 'redirectToGitHub'])->name('auth.github');
Route::get('login/github/callback', [GithubController::class, 'handleGithubCallback']);

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('todo.profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::post('/profile/unlink/{provider}', [ProfileController::class, 'unlinkProvider'])->name('profile.unlinkProvider');
    Route::post('/profile/send-reset-code', [ProfileController::class, 'sendPasswordResetCode'])->name('profile.sendResetCode');
    Route::post('/profile/verify-reset-code', [ProfileController::class, 'verifyPasswordResetCode'])->name('profile.verifyResetCode');
    Route::group(['prefix' => 'todo'], function () {
        Route::get('/', [TodoController::class, 'index'])->name('todo.index');
        Route::post('/store', [TodoController::class, 'store'])->name('todo.store');
        Route::delete('/destroy/{id}', [TodoController::class, 'destroy'])->name('todo.destroy');
        Route::post('/selesai/{id}', [TodoController::class, 'selesai'])->name('todo.selesai');
        Route::get('/edit/{id}', [TodoController::class, 'edit'])->name('todo.edit');
        Route::put('/update/{id}', [TodoController::class, 'update'])->name('todo.update');
        Route::get('/search', [TodoController::class, 'search'])->name('todo.search');
    });
});
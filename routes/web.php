<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\DashboardController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'todo'], function () {
    Route::post('/store', [TodoController::class, 'store'])->name('todo.store');
    Route::delete('/{id}', [TodoController::class, 'destroy'])->name('todo.destroy');
    Route::post('/{id}/complete', [TodoController::class, 'selesai'])->name('todo.selesai');
    Route::get('/edit/{id}', [TodoController::class, 'edit'])->name('todo.edit');
    Route::put('/update/{id}', [TodoController::class, 'update'])->name('todo.update');
    Route::get('/search', [TodoController::class, 'search'])->name('todo.search');
});

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/login/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('auth/github', [GithubController::class, 'redirectToGitHub'])->name('auth.github');
Route::get('login/github/callback', [GithubController::class, 'handleGithubCallback']);


Route::get('/todo', [TodoController::class, 'index'])->name('todo.index');
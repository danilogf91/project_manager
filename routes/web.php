<?php

use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->group(function () {
        Route::get('/dashboard', [ViewController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [ViewController::class, 'users'])->name('users');
        Route::get('/projects', [ViewController::class, 'projects'])->name('projects');
        Route::get('/projects/{id}', [ViewController::class, 'projectsData'])->name('projectsData');
        Route::get('/data/{id?}', [ViewController::class, 'data'])->name('data');
    });

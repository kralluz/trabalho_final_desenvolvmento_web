<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

// Rota para o exemplo de API
Route::get('/api-exemplo', function () {
    return Inertia::render('api-example');
})->name('api.exemplo');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

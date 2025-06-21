<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('react');
})->name('home');

Route::get('/{path?}', function () {
    return view('react');
})->where('path', '.*')->name('react-spa');

// require __DIR__.'/settings.php';
// require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'app')->name('home');

// After adding Vue Router (history mode), switch to catch-all so deep links work:
// Route::get('/{any}', fn () => view('app'))
//    ->where('any', '.*')
//    ->name('spa');

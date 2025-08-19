<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Use the session (web) guard, not auth:sanctum
Route::middleware('auth')->get('/me', fn (Request $request) => $request->user())
     ->name('auth.me');

Route::middleware(['auth', 'admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {
         Route::get('/ping', fn () => response()->json(['ok' => true]))->name('ping');
     });

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

// LOGIN (session + Sanctum "stateful" cookies)
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => [ 'required', 'email' ],
        'password' => [ 'required' ],
    ]);

    if ( !Auth::attempt($credentials, $request->boolean('remember')) ) {
        throw ValidationException::withMessages([
            'email' => [ 'The provided credentials are incorrect.' ],
        ]);
    }

    // prevent session fixation
    $request->session()->regenerate();

    // include user so the SPA doesn’t need an extra request (optional)
    return response()->json([ 'user' => $request->user() ], 200);
})->middleware('guest')->name('api.login');

// CURRENT USER (requires session-authenticated request)
Route::middleware('auth:sanctum')->get('/user', fn(Request $r) => $r->user())
     ->name('api.user');

// LOGOUT (invalidate session + rotate CSRF token)
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return response()->noContent(); // 204
})->middleware('auth')->name('api.logout');

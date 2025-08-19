<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| Public (guest) endpoints
|--------------------------------------------------------------------------
| - Session login for SPA (Sanctum "stateful" cookies)
| - Throttled to reduce brute force attempts
*/
Route::middleware(['guest', 'throttle:login'])->post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],   // currently email-only; extend later for username
        'password' => ['required'],
    ]);

    if (! Auth::attempt($credentials, $request->boolean('remember'))) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    // Prevent session fixation
    $request->session()->regenerate();

    return response()->json(['user' => $request->user()], 200);
})->name('auth.login');


/*
|--------------------------------------------------------------------------
| Authenticated (any logged-in user)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {

    // Current user
    Route::get('/me', fn (Request $r) => $r->user())->name('auth.me');

    // Session logout
    Route::post('/logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent(); // 204
    })->name('auth.logout');

    /*
    |--------------------------------------------------------------------------
    | Admin-only
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/ping', fn () => response()->json(['ok' => true]))->name('ping');
        // Add more admin endpoints here (users, categories, items management)
    });
});

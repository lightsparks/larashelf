<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::post('/login', function (Request $request) {
    $cred = $request->validate([
        'email' => [ 'required', 'email' ],
        'password' => [ 'required' ],
    ]);
    if ( !Auth::attempt($cred, remember: true) ) {
        return response()->json([ 'message' => 'Invalid credentials' ], 422);
    }
    $request->session()->regenerate();
    return response()->json([ 'message' => 'ok' ]);
});

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return response()->json([ 'message' => 'ok' ]);
});

Route::middleware('auth:sanctum')->get('/user', fn(Request $r) => $r->user());

Route::get('/ping', fn() => 'pong');
Route::get('/session-check', function () {
    return ['auth' => Auth::check()];
});

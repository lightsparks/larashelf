<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

Route::prefix('api')->group(function () {
    Route::middleware([ 'guest', 'throttle:login' ])->post('/login', function (Request $request) {

        // Accept either 'identifier' (preferred) or 'email' from the CMS
        $request->merge([
            'identifier' => $request->input('identifier', $request->input('email')),
        ]);

        $data = $request->validate([
            'identifier' => [ 'required', 'string' ],   // email OR username
            'password' => [ 'required', 'string' ],
            'remember' => [ 'sometimes', 'boolean' ],
        ]);

        $identifier = (string) $data[ 'identifier' ];
        $password = (string) $data[ 'password' ];
        $remember = (bool) ($data[ 'remember' ] ?? false);

        // If it looks like an email, enforce RFC email validation.
        if (str_contains($identifier, '@')) {
            validator(['identifier' => $identifier], [
                'identifier' => ['email'],
            ])->validate();
        }

        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt([$field => $identifier, 'password' => $password], $remember)) {
            throw ValidationException::withMessages([
                'identifier' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Prevent session fixation
        $request->session()->regenerate();

        return response()->json([ 'user' => $request->user() ], 200);

    })->name('auth.login');

    Route::middleware('auth')->post('/logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();

    })->name('auth.logout');
});

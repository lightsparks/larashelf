<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('logs in with valid credentials and returns me, then logs out', function () {
    $user = User::factory()->create([
        'password' => Hash::make('secret123'),
    ]);

    // Login (session guard)
    $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'secret123',
    ])->assertOk()
         ->assertJsonPath('user.email', $user->email);

    // Me (session persists automatically in tests)
    $this->getJson('/api/me')
         ->assertOk()
         ->assertJsonPath('email', $user->email);

    // Logout (invalidates session)
    $this->postJson('/api/logout')->assertNoContent();

    // Me after logout => 401
    $this->getJson('/api/me')->assertUnauthorized();
});

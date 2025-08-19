<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('logs in with valid credentials and returns me, then logs out', function () {
    $user = User::factory()->create([
        'password' => Hash::make('secret123'),
    ]);

    // Login
    $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'secret123',
    ])->assertOk()
         ->assertJsonPath('user.email', $user->email);

    // Me
    $this->getJson('/api/me')
         ->assertOk()
         ->assertJsonPath('email', $user->email);

    // Logout
    $this->postJson('/api/logout')->assertNoContent();

    // Me after logout
    $this->getJson('/api/me')->assertUnauthorized();
});
